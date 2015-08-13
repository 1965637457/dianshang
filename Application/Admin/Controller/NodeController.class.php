<?php
namespace Admin\Controller;

class NodeController extends TreeController{
    //put your code here
    public function _before_add(){
        $this->assign('pid',I('get.pid',0,'intval'));
        $dataset = M(CONTROLLER_NAME)->where('grade<4')->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    public function _before_edit(){
        $id = I('get.id',0,'intval');
        $condition = array(
            'id' => array('neq', $id),
            'grade' => array('lt',4)
        );
        $dataset = M(CONTROLLER_NAME)->where($condition)->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
    }
    
    public function addController(){
        $dataset = M(CONTROLLER_NAME)->where('grade<3')->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('parents', $list);
        $this->display();
    }
    public function insertController(){
        $pid = I('post.pid',0,'intval');
        $pid <= 0 && $this->error('参数错误');
        $_M = D(CONTROLLER_NAME);
        $parent = $_M->find($pid);
        if($parent['grade']!=2){
            $this->error('请选择第二级为上级组');
        }
        $_POST['grade'] = $parent['grade'] + 1;
        $_POST['route'] = $parent['route'].','.$parent['id'];
        if($_M->create()){
            $title_cn = $_M->title_cn;
            $title_en = $_M->title_en;
            $grade = $_M->grade;
            $route = $_M->route;
            $name = $_M->name;
            $module_id = $_M->add();
            \Think\Build::buildController('Admin', $name);
            \Think\Build::buildModel('Admin', $name);
            $action = I('post.action','');
            $_default_actions = array(
                'index' => array('title_cn'=> $title_cn.'列表', 'title_en'=>$title_en.' List', 'status' => 1, 'show_top' => 1),
                'add' => array('title_cn'=> '新增'.$title_cn, 'title_en'=> 'Add '.$title_en, 'status' => 1, 'show_top' => 0),
                'insert' => array('title_cn'=> '插入', 'title_en'=> 'Insert', 'status' => 0, 'show_top' => 0),
                'edit' => array('title_cn'=> '编辑', 'title_en'=> 'Edit', 'status' => 0, 'show_top' => 0),
                'update' => array('title_cn'=> '更新', 'title_en'=> 'Update', 'status' => 0, 'show_top' => 0),
                'delete' => array('title_cn'=> '删除', 'title_en'=> 'Delete', 'status' => 0, 'show_top' => 0),
                'search' => array('title_cn'=> '搜索', 'title_en'=> 'Search', 'status' => 0, 'show_top' => 0),
                'sort' => array('title_cn'=> '排序', 'title_en'=> 'Sort', 'status' => 0, 'show_top' => 0),
                'setStatus' => array('title_cn'=> '设置状态', 'title_en'=> 'Set Status', 'status' => 0, 'show_top' => 0),
                'read' => array('title_cn'=> '查看', 'title_en'=> 'View', 'status' => 0, 'show_top' => 0),
            );
            if(!empty($action)){
                $_POST = array('pid'=>$module_id,'grade'=>$grade+1,'route'=>$route.','.$module_id);
                foreach ($action as $vo){
                    if( !empty($_default_actions[$vo]) ){
                        $_POST['title_cn'] = $_default_actions[$vo]['title_cn'];
                        $_POST['title_en'] = $_default_actions[$vo]['title_en'];
                        $_POST['name'] = $vo;
                        $_POST['status'] = $_default_actions[$vo]['status'];
                        if($_M->create()){
                            $_M->add();
                        }
                    }
                }
            }
            redirect(cookie('redirectUrl'));
        }
        $this->error('参数错误');
    }
}

?>
