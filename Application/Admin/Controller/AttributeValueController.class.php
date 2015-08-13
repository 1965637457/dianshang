<?php
namespace Admin\Controller;
class AttributeValueController extends CommonController {
    
    public function add(){
        $attribute_id = I('get.attrid', 0, 'intval');
        $this->assign('attribute_id', $attribute_id);
        $this->display();
    }
    protected function _trigger_insert($id){
        $result = M(CONTROLLER_NAME)->find($id);
        $this->assign('data', json_encode($result));
        $this->display();
        exit;
    }
}