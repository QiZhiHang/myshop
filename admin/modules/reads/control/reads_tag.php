<?php
/**
 * 资讯文章分类
 *
 *
 *
 ** 本系统由FeiWa mall w w i.com提供
 */

//use FeiWa\Tpl;

defined('ByFeiWa') or exit('Access Invalid!');
class reads_tagControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('reads');
    }

    public function indexFeiwa() {
        $this->reads_tag_listFeiwa();
    }

    /**
     * 资讯文章分类列表
     **/
    public function reads_tag_listFeiwa() {
        $model = Model('reads_tag');
        $list = $model->getList(TRUE, null, 'tag_id desc');
        $this->show_menu('list');
        Tpl::output('list',$list);
        Tpl::setDirquna('reads');
Tpl::showpage("reads_tag.list");
    }

    /**
     * 资讯文章分类添加
     **/
    public function reads_tag_addFeiwa() {
        $this->show_menu('add');
        Tpl::setDirquna('reads');
Tpl::showpage('reads_tag.add');
    }

    /**
     * 资讯文章分类保存
     **/
    public function reads_tag_saveFeiwa() {
        $obj_validate = new Validate();
        $validate_array = array(
            array('input'=>$_POST['tag_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"20",'message'=>Language::get('tag_name_error')),
            array('input'=>$_POST['tag_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('tag_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['tag_name'] = trim($_POST['tag_name']);
        $param['tag_sort'] = intval($_POST['tag_sort']);
        $model_class = Model('reads_tag');
        $result = $model_class->save($param);
        if($result) {
            $this->log(Language::get('reads_log_tag_save').$result, 1);
            showMessage(Language::get('tag_add_success'),'index.php?app=reads_tag&feiwa=reads_tag_list');
        } else {
            $this->log(Language::get('reads_log_tag_save').$result, 0);
            showMessage(Language::get('tag_add_fail'),'index.php?app=reads_tag&feiwa=reads_tag_list','','error');
        }


    }

    /**
     * 资讯标签排序修改
     */
    public function update_tag_sortFeiwa() {
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $this->update_tag('tag_sort', $new_sort);
        }
    }

    /**
     * 资讯标签标题修改
     */
    public function update_tag_nameFeiwa() {
        $new_value = trim($_GET['value']);
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array('input'=>$new_value,'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('tag_name_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('tag_name_error')));
            die;
        } else {
            $this->update_tag('tag_name', $new_value);
        }
    }

    /**
     * 资讯标签修改
     */
    private function update_tag($column, $new_value) {
        $tag_id = intval($_GET['id']);
        if($tag_id <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }

        $model = Model("reads_tag");
        $result = $model->modify(array($column=>$new_value),array('tag_id'=>$tag_id));
        if($result) {
            echo json_encode(array('result'=>TRUE, 'message'=>'success'));
            die;
        } else {
            echo json_encode(array('result'=>FALSE, 'message'=>Language::get('feiwa_common_save_fail')));
            die;
        }
    }

    /**
     * 资讯标签删除
     **/
     public function reads_tag_dropFeiwa() {
        $tag_id = trim($_POST['tag_id']);
        $model = Model('reads_tag');
        $condition = array();
        $condition['tag_id'] = array('in',$tag_id);
        $result = $model->drop($condition);
        if($result) {
            $this->log(Language::get('reads_log_tag_drop').$_POST['tag_id'], 1);
            showMessage(Language::get('tag_drop_success'),'');
        } else {
            $this->log(Language::get('reads_log_tag_drop').$_POST['tag_id'], 0);
            showMessage(Language::get('tag_drop_fail'),'','','error');
        }

     }

    private function show_menu($menu_key) {
        $menu_array = array(
            'list'=>array('menu_type'=>'link','menu_name'=>Language::get('feiwa_list'),'menu_url'=>'index.php?app=reads_tag&feiwa=reads_tag_list'),
            'add'=>array('menu_type'=>'link','menu_name'=>Language::get('feiwa_new'),'menu_url'=>'index.php?app=reads_tag&feiwa=reads_tag_add'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }


}
