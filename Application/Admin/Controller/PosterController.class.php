<?php
namespace Admin\Controller;
class PosterController extends CommonController {

    public function add(){
        $space_id = I('get.spaceid', 0, 'intval');
        $this->assign('space_id', $space_id);
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