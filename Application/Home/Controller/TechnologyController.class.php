<?php
namespace Home\Controller;
class TechnologyController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_purple');
        $this->assign('top_banner', $this->_getAdvert(5, true));
    }
    public function index(){
        $this->_getFirstArticle();
        
//        $this->_getSideMenu();
        
        $this->display('detail');
    }
//    public function exhibitor(){
//        $this->_getRewriteArticle(ACTION_NAME);
//        $this->_getSideMenu();
//        
//        $_M = M('Exhibitor');
//        $list = $_M->where('status = 1')->order('sort desc,id')->select();
//        $this->assign('list', $list);
//        
//        $this->display();
//    }
    
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