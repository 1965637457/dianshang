<?php
namespace Admin\Controller;
class RoleController extends TreeController {

    public function index(){
        //TODO: 非超级管理员只能看到直系下级角色
        if(session(C('ADMIN_AUTH_KEY'))){
            parent::_tree();
        }else{
            $auth = session(C('USER_AUTH_KEY'));
            $_M = M(CONTROLLER_NAME);
            $list = $_M->where(array('pid'=>$auth['role_id']))->order('sort desc,id')->select();
            $this->assign('list', $list);
            cookie('redirectUrl', __SELF__);
            $this->display();
        }
    }
    public function _before_add(){
        if(session(C('ADMIN_AUTH_KEY'))){
            $dataset = M(CONTROLLER_NAME)->order('route,sort desc,id')->select();
            $list = list_to_level($dataset);
            array_unshift($list, array('id'=>0,'name'=>'==根节点==','grade'=>1));
        }else{
            $auth = session(C('USER_AUTH_KEY'));
            $list = array(array('id'=>$auth['role_id'],'name'=>'==添加子角色==','grade'=>1));
        }
            
        $this->assign('parents', $list);
    }
    public function _before_edit(){
        if(session(C('ADMIN_AUTH_KEY'))){
            $id = I('get.id',0,'intval');
            $condition = array(
                'id' => array('neq', $id),
            );
            $dataset = M(CONTROLLER_NAME)->where($condition)->order('route,sort desc,id')->select();
            $list = list_to_level($dataset);
        }else{
            $auth = session(C('USER_AUTH_KEY'));
            $list = array(array('id'=>$auth['role_id'],'name'=>'==添加子角色==','grade'=>1));
        }
        $this->assign('parents', $list);
    }
    
    /**
     * 设置权限
     */
    public function privilege(){
        $role_id = I('get.rid',0,'intval');
        $role_id <= 0 && $this->error('参数有误');
        $_Node = M('Node');
        //提取正在登录的管理员的权限
        if (session(C('ADMIN_AUTH_KEY'))) {
            $list = $_Node->order('route,sort desc,id')->select();
        }else{
            // 管理员角色
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            // 管理员拥有权限
            $list = $_Node->query("SELECT a.* FROM __TABLE__ a WHERE a.id in ( select node_id from smi_access where role_id ={$auth_role_id} ) order by a.route,a.sort desc,a.id");
        }
        $list = list_to_tree($list);
        //提取待处理角色本身拥有的权限
        $_Access = M('Access');
        $map = array(
            'role_id' => $role_id,
            'grade' => array('in',array(2,3,4))
        );
        $access = $_Access->where($map)->getField('node_id,role_id');
        $this->assign('access', $access);
        $this->assign('list', $list);
        $this->assign('role_id', $role_id);
        
        $this->display();
    }
    /**
     * 完成权限设置
     */
    public function setPrivilege(){
        $role_id = I('post.role_id',0,'intval');
        !$role_id && $this->error('授权有误', U('index'));
        
        $_Node = M('Node');
        $_Access = M('Access');
        // 清除所有已有权限
        $_Access->where('role_id='.$role_id)->delete();
        //分配到的权限
        $actions = $_POST['node'];
        //分配权限为空，即
        if(!$actions){
            $this->error('无法设置空权限');
        }
        //分配到的权限非空,处理权限
        $ids = array();
        if(!empty($actions)){
            foreach($actions as $action){
                $tmp = explode(',', $action);
                foreach($tmp as $aid){
                    $ids[$aid] = 1;
                }
            }
            $ids = array_keys($ids);
        }
        //超级管理员
        if (session(C('ADMIN_AUTH_KEY'))) {
            $map = array(
                'id' => array('in',$ids)
            );
            $list = $_Node->where($map)->order('route,sort desc,id')->select();
        }else{
            // 管理员角色
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            $table = array('role' => C('RBAC_ROLE_TABLE'), 'user' => C('RBAC_USER_TABLE'), 'access' => C('RBAC_ACCESS_TABLE'), 'node' => C('RBAC_NODE_TABLE'));
            $list = $_Access->query("
                SELECT node.id,node.name,node.title,node.pid,node.grade
                FROM __TABLE__ access
                LEFT JOIN ".$table['node']." as node ON access.node_id=node.id
                WHERE access.role_id = {$auth_role_id} AND access.node_id in (".implode(',',$ids).")
                ORDER BY node.route,node.sort desc,node.id
            ");
        }
        $list = list_to_level($list);
        //无树型权限
        if(empty($list)){
            $this->error('无法设置权限');
        }else{
            $data = array(
                'role_id' => $role_id,
                'status' => 1,
            );
            foreach($list as $v){
                $data['grade'] = $v['grade'];
                $data['node_id'] = $v['id'];
                $_Access->add($data);
            }
        }
        redirect(U('index'));
    }
    
    
    
}