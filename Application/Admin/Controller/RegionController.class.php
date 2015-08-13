<?php
namespace Admin\Controller;
class RegionController extends TreeController {
    
    public function index(){
        $pid = I('get.pid', 0, 'intval');
        $condition = array(
            'pid' => $pid,
        );
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->field("SQL_CALC_FOUND_ROWS *")->where($condition)->limit($_P->firstRow.','.$_P->listRows)->order('sort desc,id')->select();
        if(!$list){
            redirect(U('edit', array('id'=>$pid)));
        }
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    
}