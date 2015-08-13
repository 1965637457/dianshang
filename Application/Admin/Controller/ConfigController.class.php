<?php
namespace Admin\Controller;
class ConfigController extends TreeController {
    
    public function _before_add(){
        $this->assign('pid',I('get.pid',0,'intval'));
        $dataset = M(CONTROLLER_NAME)->where('grade<2')->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    public function _before_edit(){
        $id = I('get.id',0,'intval');
        $condition = array(
            'id' => array('neq', $id),
            'grade' => array('lt',2)
        );
        $dataset = M(CONTROLLER_NAME)->where($condition)->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    public function setting(){
        $_M = M(CONTROLLER_NAME);
        $dataset = $_M->order('route,sort desc,id')->select();
        $list = list_to_tree($dataset);
        $this->assign('list', $list);
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    public function updateSetting(){
        $configs = $_POST['config'];
        if(empty($configs)){
            $this->error('更新失败，无配置值');
        }
        $_M = M(CONTROLLER_NAME);
        foreach($configs as $id => $config_value){
            $_M->save(array(
                'id' => $id,
                'config_value' => $config_value
            ));
        }
        //缓存配置信息
        $list = $_M->where('grade=2')->getField('code,config_value');
        F('~config', $list);
        
        redirect(U('setting'));
    }
}