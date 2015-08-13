<?php
namespace Admin\Controller;
class CategoryController extends TreeController {
    
    /**
     * 树型列表
     */
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $dataset = $_M->alias('c')->field('c.*, t.type_name')->join("LEFT JOIN __TYPE__ t ON t.id = c.type_id")->order('c.route,c.sort desc,c.id')->select();
        $list = list_to_level($dataset);
        $this->assign('list', $list);
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    protected function _trigger_add(){
        $this->assign('type_list', M('Type')->select());
    }
    protected function _trigger_edit(&$data){
        $this->assign('type_list', M('Type')->select());
    }
}