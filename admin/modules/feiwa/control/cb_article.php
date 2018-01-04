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
class reads_articleControl extends SystemControl{
    //文章状态草稿箱
    const ARTICLE_STATE_DRAFT = 1;
    //文章状态待审核
    const ARTICLE_STATE_VERIFY = 2;
    //文章状态已发布
    const ARTICLE_STATE_PUBLISHED = 3;
    //文章状态回收站
    const ARTICLE_STATE_RECYCLE = 4;

    public function __construct(){
        parent::__construct();
        Language::read('reads');
    }

    public function indexFeiwa() {
        $this->reads_article_listFeiwa();
    }

    /**
     * 资讯文章列表
     **/
    public function reads_article_listFeiwa()
    {
        $this->doaction(0, 'list');
    }

    /**
     * 待审核文章列表
     */
    public function reads_article_list_verifyFeiwa()
    {
        $this->doaction(self::ARTICLE_STATE_VERIFY, 'list_verify');
    }

    /**
     * 已发布文章列表
     */
    public function reads_article_list_publishedFeiwa()
    {
        $this->doaction(self::ARTICLE_STATE_PUBLISHED, 'list_published');
    }

    protected function doaction($state, $menuKey)
    {
        $this->show_menu($menuKey);
        Tpl::output('currentState', $state);

        $states = $this->get_article_state_list();
        Tpl::output('states', $states);

        Tpl::setDirquna('reads');
Tpl::showpage("reads_article.list");
    }

    public function reads_article_list_xmlFeiwa()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['article_title']))) {
                $condition['article_title'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['article_publisher_name']))) {
                $condition['article_publisher_name'] = $q;
            }

            if (strlen($q = trim((string) $_REQUEST['article_commend_flag']))) {
                $condition['article_commend_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['article_commend_image_flag']))) {
                $condition['article_commend_image_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['article_comment_flag']))) {
                $condition['article_comment_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['article_attitude_flag']))) {
                $condition['article_attitude_flag'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['article_state']))) {
                $condition['article_state'] = (int) $q;
            }

        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'article_title':
                        $condition[$_REQUEST['qtype']] = array('like', '%' . $q . '%');
                        break;
                    case 'article_publisher_name':
                        $condition[$_REQUEST['qtype']] = $q;
                        break;
                }
            }
        }

        if ($_GET['article_state']) {
            $condition['article_state'] = (int) $_GET['article_state'];
        }

        $model_article = Model('reads_article');
        $list = (array) $model_article->getList($condition, $_REQUEST['rp'], 'article_id desc');

        $data = array();
        $data['now_page'] = $model_article->shownowpage();
        $data['total_num'] = $model_article->gettotalnum();

        $states = $this->get_article_state_list();

        foreach ($list as $val) {
            $o = '<a class="btn red" href="javascript:;" data-j="drop"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['article_state'] == self::ARTICLE_STATE_VERIFY) {
                $o .= '<li><a href="javascript:;" data-j="audit">审核文章</a></li>';
            }
            if ($val['article_state'] == self::ARTICLE_STATE_PUBLISHED) {
                $o .= '<li><a href="javascript:;" data-j="callback">收回文章</a></li>';
            }

            $o .= '<li><a target="_blank" href="' .
                    READS_SITE_URL .
                    '/index.php?app=article&feiwa=article_detail&article_id=' .
                    $val['article_id'] .
                    '">查看文章</a></li>';

            if ($val['article_commend_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="article_commend_flag" data-val="0">取消推荐文章</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="article_commend_flag" data-val="1">推荐文章</a></li>';
            }
            if ($val['article_commend_image_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="article_commend_image_flag" data-val="0">取消推荐图文</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="article_commend_image_flag" data-val="1">推荐图文</a></li>';
            }
            if ($val['article_comment_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="article_comment_flag" data-val="0">关闭评论</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="article_comment_flag" data-val="1">开启评论</a></li>';
            }
            if ($val['article_attitude_flag'] == 1) {
                $o .= '<li><a href="javascript:;" data-j="article_attitude_flag" data-val="0">关闭心情</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-j="article_attitude_flag" data-val="1">开启心情</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;

            $i['article_sort'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="article_sort">' .
                $val['article_sort'] . '</span>';

            $i['article_title'] = $val['article_title'];

            $img = getREADSArticleImageUrl($val['article_attachment_path'], $val['article_image']);
            $i['img'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip" onMouseOut="toolTip()" onMouseOver="toolTip('<img src=\'{$img}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['article_publisher_name'] = $val['article_publisher_name'];

            $i['article_click'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="article_click">' .
                $val['article_click'] . '</span>';

            $i['article_commend_flag'] = $val['article_commend_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['article_commend_image_flag'] = $val['article_commend_image_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['article_comment_flag'] = $val['article_comment_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-circle"></i>开启</span>'
                : '<span class="no"><i class="fa fa-ban"></i>关闭</span>';

            $i['article_attitude_flag'] = $val['article_attitude_flag'] == 1
                ? '<span class="yes"><i class="fa fa-check-circle"></i>开启</span>'
                : '<span class="no"><i class="fa fa-ban"></i>关闭</span>';

            $i['article_state'] = $states[$val['article_state']]['text'];

            $data['list'][$val['article_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 资讯文章审核
     */
    public function reads_article_verifyFeiwa() {
        if(intval($_REQUEST['verify_state']) === 1) {
            $this->reads_article_state_modify(self::ARTICLE_STATE_PUBLISHED);
        } else {
            $this->reads_article_state_modify(self::ARTICLE_STATE_DRAFT, array('article_verify_reason' => $_POST['verify_reason']));
        }
    }

    /**
     * 资讯文章收回
     */
    public function reads_article_callbackFeiwa() {
        $this->reads_article_state_modify(self::ARTICLE_STATE_DRAFT);
    }

    /**
     * 修改文章状态
     */
    private function reads_article_state_modify($new_state, $param = array()) {
        $article_id = $_REQUEST['article_id'];
        $model_article = Model('reads_article');
        $param['article_state'] = $new_state;
        $model_article->modify($param, array('article_id'=>array('in', $article_id)));
        showMessage(Language::get('feiwa_common_op_succ'), '');
    }

    /**
     * 资讯文章分类排序修改
     */
    public function update_article_sortFeiwa() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("reads_article");
            $result = $model_class->modify(array('article_sort'=>$new_sort),array('article_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /**
     * 资讯文章分类排序修改
     */
    public function update_article_clickFeiwa() {
        if(intval($_GET['id']) <= 0 || intval($_GET['value']) < 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));die;
        }
        $model_class = Model("reads_article");
        $result = $model_class->modify(array('article_click'=>$_GET['value']),array('article_id'=>$_GET['id']));
        if($result) {
            echo json_encode(array('result'=>TRUE,'message'=>''));die;
        } else {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));die;
        }
    }


    /**
     * 资讯文章删除
     **/
     public function reads_article_dropFeiwa() {
        $article_id = trim($_REQUEST['article_id']);
        $model_article = Model('reads_article');
        $condition = array();
        $condition['article_id'] = array('in',$article_id);
        $result = $model_article->drop($condition);
        if($result) {
            $this->log(Language::get('reads_log_article_drop').$article_id, 1);
            showMessage(Language::get('feiwa_common_del_succ'),'');
        } else {
            $this->log(Language::get('reads_log_article_drop').$article_id, 0);
            showMessage(Language::get('feiwa_common_del_fail'),'','','error');
        }
     }

    /**
     * ajax操作
     */
    public function ajaxFeiwa(){
        if (intval($_GET['id']) < 1) {
            exit('false');
        }

        switch ($_GET['column']) {
            case 'article_commend_flag':
            case 'article_commend_image_flag':
            case 'article_comment_flag':
            case 'article_attitude_flag':
                break;

            default:
                exit('false');
        }

        $model= Model('reads_article');
        $update[$_GET['column']] = trim($_GET['value']);
        $condition['article_id'] = intval($_GET['id']);
        $model->modify($update,$condition);

        echo 'true';die;
    }


    /**
     * 获取文章状态列表
     */
    private function get_article_state_list() {
        $array = array();
        $array[self::ARTICLE_STATE_DRAFT] = array('text'=>Language::get('reads_text_draft'));
        $array[self::ARTICLE_STATE_VERIFY] = array('text'=>Language::get('reads_text_verify'));
        $array[self::ARTICLE_STATE_PUBLISHED] = array('text'=>Language::get('reads_text_published'));
        $array[self::ARTICLE_STATE_RECYCLE] = array('text'=>Language::get('reads_text_recycle'));
        return $array;
    }

    private function show_menu($menu_key) {
        $menu_array = array(
            'list'=>array('menu_type'=>'link','menu_name'=>Language::get('feiwa_list'),'menu_url'=>'index.php?app=reads_article&feiwa=reads_article_list'),
            'list_verify'=>array('menu_type'=>'link','menu_name'=>Language::get('reads_article_list_verify'),'menu_url'=>'index.php?app=reads_article&feiwa=reads_article_list_verify'),
            'list_published'=>array('menu_type'=>'link','menu_name'=>Language::get('reads_article_list_published'),'menu_url'=>'index.php?app=reads_article&feiwa=reads_article_list_published'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }

}
