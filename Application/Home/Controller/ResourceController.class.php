<?php
namespace Home\Controller;
class ResourceController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_light_red');
        $this->assign('top_banner', $this->_getAdvert(7, true));
    }
    public function index(){
        $this->_getFirstArticle();
        
//        $this->_getSideMenu();
        
        $this->display('detail');
    }
    
    public function _empty($action){
        
        $this->_getRewriteArticle($action);
        
//        $this->_getSideMenu();
        
        $this->display('detail');
    }
    
    public function detail(){
        $this->_getArticleById();
        
//        $this->_getSideMenu();
        
        $this->display('detail');
    }
}