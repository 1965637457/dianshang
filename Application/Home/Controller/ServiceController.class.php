<?php
namespace Home\Controller;
class ServiceController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_blue');
        $this->assign('top_banner', $this->_getAdvert(4, true));
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