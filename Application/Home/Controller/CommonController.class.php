<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
    
    
    public function _initialize(){
        /* 读取数据库中的配置 */
        $this->_getConfig();
        //主导航
        $this->_getNavigation();
        //底部
        $this->_getBottomInfo();
    }
    
    protected function _getNavigation(){
        $nav_list = array();
        $nav_list['About'] = $this->_getDataList('About');
        $nav_list['News'] = $this->_getDataList('NewsCategory');
        $nav_list['Product'] = M('ProductCategory')->where(array('status'=>1))->order('sort desc,id')->getField('id,cate_name,rewriteuri,image,short_desc');
        if($nav_list['Product']){
            $_Product = M('Product');
            $list = $_Product->where(array('status'=>1))->order('cate_id,sort desc,id')->select();
            if($list){
                foreach ($list as $product){
                    $nav_list['Product'][$product['cate_id']]['_list'][] = $product;
                }
            }
        }
        $nav_list['Service'] = $this->_getDataList('Service');
        $nav_list['Resource'] = list_to_tree(M('Resource')->where(array('status'=>1))->order('route,sort desc,id')->select());
        $nav_list['Quality'] = $this->_getDataList('Quality');
        $nav_list['Technology'] = $this->_getDataList('Technology');
        $nav_list['Info'] = $this->_getDataList('Info');
        $this->assign('nav_list', $nav_list);
    }
    protected function _getBottomInfo(){
        $_M = M('Product');
        $bottom_pros = $_M->where('status=1 AND is_bottom=1')->order('sort desc,id')->select();
        $this->assign('bottom_pros', $bottom_pros);
    }
    protected function _getConfig(){
        $config =   S('DB_CONFIG_DATA','',array('expire'=>60));
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置
    }
    /**
     * 读取第一篇文章
     */
    protected function _getFirstArticle(){
        $this->assign('article', $this->_getFirstData());
    }
    protected function _getFirstData($m=CONTROLLER_NAME){
        return M($m)->where(array('status'=>1))->order('sort desc,id')->find();
    }
    /**
     * 读取通过ID传值的文章
     */
    protected function _getArticleById(){
        $data = $this->_getDataById();
        if(!$data || $data['status']!=1){
            redirect('index');
        }
        $this->assign('article', $data);
    }
    protected function _getDataById($m=CONTROLLER_NAME){
        $id = I('get.id', 0, 'intval');
        if($id <= 0){
            return false;
        }
        return M($m)->find($id);
    }
    /**
     * 通过重写路径读取文章
     * @param type $action
     */
    protected function _getRewriteArticle($action, $m=CONTROLLER_NAME){
        $data = $this->_getRewriteData($action, $m);
        if(!$data || $data['status']!=1){
            redirect('index');
        }
        $this->assign('article', $data);
    }
    protected function _getRewriteData($action, $m=CONTROLLER_NAME){
        $_M = M($m);
        return $_M->where(array('rewriteuri'=>$action))->find();
    }
    /**
     * 按广告位获取广告
     * @param type $position_id
     * @return boolean
     */
    protected function _getAdvert($position_id, $get_one = false){
        $_AdvertPosition = M('AdvertPosition');
        $position = $_AdvertPosition->find($position_id);
        if(!$position || $position['status'] != 1){
            return false;
        }
        $_Advert = M('Advert');
        $dataset = $_Advert->where(array('position_id'=>$position_id,'status'=>1))->order('sort desc,id')->select();
        if($get_one){
            return $dataset[0];
        }else{
            return $dataset;
        }
    }
    /**
     * 获取左侧菜单列表
     */
    protected function _getSideMenu(){
        $side_menu = $this->_getDataList();
        $this->assign('side_menu', $side_menu);
    }
    protected function _getDataList($m=CONTROLLER_NAME){
        return M($m)->where(array('status'=>1))->order('sort desc,id')->select();
    }
}

?>
