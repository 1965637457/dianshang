<?php
namespace Home\Controller;
class QualityController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_cyan');
        $this->assign('top_banner', $this->_getAdvert(8, true));
    }
    public function index(){
        $this->_getFirstArticle();
        
//        $this->_getSideMenu();
        
        $this->display('detail');
    }
    
    public function download(){
        $this->_getRewriteArticle(ACTION_NAME);
        
        $this->_getSideMenu();
        
        $_M = M('ExhibitFile');
        $condition = array('status'=>1);
        $count = $_M->where($condition)->count();
        $_P = new \Think\Page($count, 10);
        $list = $_M->where($condition)->order('id desc')->limit($_P->firstRow.','.$_P->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
        
        $this->display();
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