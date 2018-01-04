<?php
/**
 * 交易快照
 *
 *
 *
 * * @FeiWa (c) 2015-2018 FeiWa   (http://www.feiwa.org)
 * @license    http://www.feiwa.org
 * @link       联系电话：0539-889333 客服QQ：2116198029
 * @since      山东破浪网络科技有限公司提供技术支持 授权请购买FeiWa授权
 */
defined('ByFeiWa') or exit('Access Invalid!');
class order_snapshotModel extends Model {

    public function __construct() {
        parent::__construct('order_snapshot');
    }

    /**
     * 由订单商品表主键取得交易快照信息
     * @param int $rec_id
     * @param int $goods_id
     * @return array
     */
    public function getSnapshotInfoByRecid($rec_id,$goods_id) {
        $info = $this->where(array('rec_id'=>$rec_id))->find();
        if (!$info) {
            return $this->createSphot($rec_id, $goods_id);
        }
        return $info;
    }

    public function createSphot($rec_id,$goods_id) {
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$goods_id),'goods_serial,goods_body,goods_commonid');
        $goods_common_info = $model_goods->getGoodsCommonInfo(array('goods_commonid'=>$goods_info['goods_commonid']),'brand_name,goods_attr,goods_custom,goods_body,plateid_top,plateid_bottom');
        $goods_common_info['goods_attr'] = unserialize($goods_common_info['goods_attr']);
        $goods_common_info['goods_custom'] = unserialize($goods_common_info['goods_custom']);
        $_attr = array();
        $_attr['货号'] = $goods_info['goods_serial'];
        $_attr['品牌'] = $goods_common_info['brand_name'];
        if (is_array($goods_common_info['goods_attr']) && !empty($goods_common_info['goods_attr'])) {
            foreach($goods_common_info['goods_attr'] as $v) {
                $_attr[$v['name']] = end($v);
            }            
        }
        if (is_array($goods_common_info['goods_custom']) && !empty($goods_common_info['goods_custom'])) {
            foreach($goods_common_info['goods_custom'] as $v) {
                $_attr[$v['name']] = $v['value'];
            }
        }

        $info = array();
        $info['rec_id'] = $rec_id;
        $info['goods_id'] = $goods_id;
        $info['create_time'] = time();
        $info['goods_attr'] = serialize($_attr);
        $info['goods_body'] = $goods_info['goods_body'] == '' ? $goods_common_info['goods_body'] : $goods_info['goods_body'];
        $model_plate = Model('store_plate');
        // 顶部关联版式
        if ($goods_common_info['plateid_top'] > 0) {
            $plate_top = $model_plate->getStorePlateInfoByID($goods_common_info['plateid_top']);
            $info['plate_top'] = $plate_top['plate_content'];
        }
        // 底部关联版式
        if ($goods_common_info['plateid_bottom'] > 0) {
            $plate_bottom = $model_plate->getStorePlateInfoByID($goods_common_info['plateid_bottom']);
            $info['plate_bottom'] = $plate_bottom['plate_content'];
        }
        $this->insert($info);
        return $info;
    }

}
