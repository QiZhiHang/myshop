<?php
/**
 * 系统文章管理
 *
 *
 *
 *
 * @山东破浪网络科技有限公司提供技术支持 授权请购买FeiWa授权
 * @license    http://www.feiwa.org
 * @link       联系电话：0539-889333 客服QQ：2116198029
 */



defined('ByFeiWa') or exit('Access Invalid!');
class documentControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('document');
    }

    /**
     * 系统文章管理首页
     */
    public function indexFeiwa(){
        $this->documentFeiwa();
        exit;
    }

    /**
     * 系统文章列表
     */
    public function documentFeiwa(){
        $model_doc  = Model('document');
        $doc_list   = $model_doc->getList();
        Tpl::output('doc_list',$doc_list);
		Tpl::setDirquna('system');
		Tpl::setDirquna('system');
        Tpl::showpage('document.index');
    }

    /**
     * 系统文章编辑
     */
    public function editFeiwa(){
        $lang   = Language::getLangContent();
        /**
         * 更新
         */
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["doc_title"], "require"=>"true", "message"=>$lang['document_index_title_null']),
                array("input"=>$_POST["doc_content"], "require"=>"true", "message"=>$lang['document_index_content_null'])
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {

                $param  = array();
                $param['doc_id']    = intval($_POST['doc_id']);
                $param['doc_title'] = trim($_POST['doc_title']);
                $param['doc_content']= trim($_POST['doc_content']);
                $param['doc_time']  = time();
                $model_doc  = Model('document');

                $result = $model_doc->updates($param);

                if ($result){
                    /**
                     * 更新图片信息ID
                     */
                    $model_upload = Model('upload');
                    if (is_array($_POST['file_id'])){
                        foreach ($_POST['file_id'] as $k => $v){
                            $v = intval($v);
                            $update_array = array();
                            $update_array['upload_id'] = $v;
                            $update_array['item_id'] = intval($_POST['doc_id']);
                            $model_upload->updates($update_array);
                            unset($update_array);
                        }
                    }

                    $url = array(
                        array(
                            'url'=>'index.php?app=document&feiwa=document',
                            'msg'=>$lang['document_edit_back_to_list']
                        ),
                        array(
                            'url'=>'index.php?app=document&feiwa=edit&doc_id='.intval($_POST['doc_id']),
                            'msg'=>$lang['document_edit_again']
                        ),
                    );
                    $this->log(L('feiwa_edit,document_index_document').'[ID:'.$_POST['doc_id'].']',1);
                    showMessage($lang['feiwa_common_save_succ'],$url);
                }else {
                    showMessage($lang['feiwa_common_save_fail']);
                }
            }
        }
        /**
         * 编辑
         */
        if(empty($_GET['doc_id'])){
            showMessage($lang['miss_argument']);
        }
        $model_doc  = Model('document');
        $doc    = $model_doc->getOneById(intval($_GET['doc_id']));

        /**
         * 模型实例化
         */
        $model_upload = Model('upload');
        $condition['upload_type'] = '4';
        $condition['item_id'] = $doc['doc_id'];
        $file_upload = $model_upload->getUploadList($condition);
        if (is_array($file_upload)){
            foreach ($file_upload as $k => $v){
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/'.$file_upload[$k]['file_name'];
            }
        }

        Tpl::output('PHPSESSID',session_id());
        Tpl::output('file_upload',$file_upload);
        Tpl::output('doc',$doc);
		Tpl::setDirquna('system');
        Tpl::showpage('document.edit');
    }

    /**
     * 系统文章图片上传
     */
    public function document_pic_uploadFeiwa(){
        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload->set('default_dir',ATTACH_ARTICLE);

        $result = $upload->upfile('fileupload');
        if ($result){
            $_POST['pic'] = $upload->file_name;
        }else {
            echo 'error';exit;
        }
        /**
         * 模型实例化
         */
        $model_upload = Model('upload');
        /**
         * 图片数据入库
        */
        $insert_array = array();
        $insert_array['file_name'] = $_POST['pic'];
        $insert_array['upload_type'] = '4';
        $insert_array['file_size'] = $_FILES['fileupload']['size'];
        $insert_array['item_id'] = intval($_POST['item_id']);
        $insert_array['upload_time'] = time();
        $result = $model_upload->add($insert_array);
        if ($result){
            $data = array();
            $data['file_id'] = $result;
            $data['file_name'] = $_POST['pic'];
            $data['file_path'] = $_POST['pic'];
            /**
             * 整理为json格式
             */
            $output = json_encode($data);
            echo $output;
        }

    }
    /**
     * ajax操作
     */
    public function ajaxFeiwa(){
        switch ($_GET['branch']){
            /**
             * 删除文章图片
             */
            case 'del_file_upload':
                if (intval($_GET['file_id']) > 0){
                    $model_upload = Model('upload');
                    /**
                     * 删除图片
                     */
                    $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ARTICLE.DS.$file_array['file_name']);
                    /**
                     * 删除信息
                     */
                    $model_upload->del(intval($_GET['file_id']));
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }
}
