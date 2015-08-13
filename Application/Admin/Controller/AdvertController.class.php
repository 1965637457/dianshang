<?php
namespace Admin\Controller;
use Think\Page;
class AdvertController extends CommonController {
    
    protected $_aOrderBy = 'sort desc,id';
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $count = $_M->alias('a')->count();
        if($count){
            $_P = new Page($count);
            $list = $_M->field("a.*, ap.position_name")->alias('a')->join("LEFT JOIN __ADVERT_POSITION__ as ap ON ap.id = a.position_id")->limit($_P->firstRow.','.$_P->listRows)->order($this->_aOrderBy)->select();
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        $this->assign('positions', M('AdvertPosition')->order('sort desc,id')->select());
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    public function search(){
        $pid = I('get.pid', 0, 'intval');
        if($pid <= 0){
            redirect('index');
        }
        $condition = array(
            'a.position_id' => $pid,
        );
        $_M = M(CONTROLLER_NAME);
        $count = $_M->alias('a')->where($condition)->count();
        if($count){
            $_P = new Page($count);
            $list = $_M->field("a.*, ap.position_name")->alias('a')->join("LEFT JOIN __ADVERT_POSITION__ as ap ON ap.id = a.position_id")->where($condition)->limit($_P->firstRow.','.$_P->listRows)->order($this->_aOrderBy)->select();
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        $this->assign('positions', M('AdvertPosition')->order('sort desc,id')->select());
        $this->assign('s_opts', array(
            'pid' => $pid
        ));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    public function _before_add(){
        $pid = I('get.pid', 0, 'intval');
        $this->assign('pid', $pid);
        
        $this->assign('positions', M('AdvertPosition')->order('sort desc,id')->select());
    }
    public function _before_edit(){
        $this->assign('positions', M('AdvertPosition')->order('sort desc,id')->select());
    }
    
}