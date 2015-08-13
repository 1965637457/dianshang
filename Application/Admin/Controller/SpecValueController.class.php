<?php
namespace Admin\Controller;
class SpecValueController extends CommonController {
    
    public function add(){
        $spec_id = I('get.specid', 0, 'intval');
        $this->assign('spec_id', $spec_id);
        $this->display();
    }
    protected function _trigger_insert($id){
        $result = M(CONTROLLER_NAME)->find($id);
        $this->assign('data', json_encode($result));
        $this->display();
        exit;
    }
    protected function _trigger_update(){
        $id = I('post.id', 0, 'intval');
        $result = M(CONTROLLER_NAME)->find($id);
        $this->assign('data', json_encode($result));
        $this->display();
        exit;
    }
}