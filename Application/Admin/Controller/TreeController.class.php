<?php

namespace Admin\Controller;
class TreeController extends CommonController{
    //put your code here
    public function index() {
        parent::_tree();
    }
    public function _before_add(){
        $this->assign('pid',I('get.pid',0,'intval'));
        $dataset = M(CONTROLLER_NAME)->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    public function _before_edit(){
        $id = I('get.id',0,'intval');
        $condition = array(
            'id' => array('neq', $id),
        );
        $dataset = M(CONTROLLER_NAME)->where($condition)->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    public function delete(){
        parent::_deleteTree();
    }
}

?>
