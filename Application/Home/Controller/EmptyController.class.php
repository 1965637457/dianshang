<?php
namespace Home\Controller;
class EmptyController extends CommonController{
    
    public function index(){
        $this->display('Public/404');
    }
    public function _empty($action_name){
        $this->display('Public/404');
    }
}

?>
