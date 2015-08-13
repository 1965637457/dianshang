<?php
namespace Admin\Controller;
use Think\Page;
class UserController extends CommonController {
    

    public function index() {
        $_M = M(CONTROLLER_NAME);
        $condition = array('u.is_super'=>0);
        //非超级管理员，只能搜索直系下级角色的管理员
        if(!session(C('ADMIN_AUTH_KEY'))){
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            $result = M('Role')->field("GROUP_CONCAT(id) as ids")->where(array('pid'=>$auth_role_id))->find();
            if($result){
                $role_ids = $result['ids'];
                $condition['u.role_id'] = array('in', $role_ids);
            }
        }
        $count = $_M->alias('u')->where($condition)->count();
        if($count){
            $_P = new Page($count);
            $list = $_M->alias('u')->field('u.*, r.name as role_name')->join('__ROLE__ r ON r.id = u.role_id')->where($condition)->limit($_P->firstRow.','.$_P->listRows)->select();
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        $this->_getFilterRoles();
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    
    public function search(){
        $rid = I('get.rid', 0, 'intval');
        $keyword = I('get.keyword', '');
        $condition = array(
            'u.is_super' => 0
        );
        //非超级管理员，只能搜索直系下级角色的管理员
        if(!session(C('ADMIN_AUTH_KEY'))){
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            $result = M('Role')->field("GROUP_CONCAT(id) as ids")->where(array('pid'=>$auth_role_id))->find();
            if($result){
                $role_ids = $result['ids'];
                $condition['u.role_id'] = array('in', $role_ids);
            }
        }
        if($rid > 0){
            $role_id_arr = explode(',', $role_ids);
            if(in_array($rid, $role_id_arr)){
                $condition['u.role_id'] = $rid;
            }
        }
        if($keyword){
            $condition['u.account'] = array('like','%'.$keyword.'%');
        }
        $_M = M(CONTROLLER_NAME);
        $count = $_M->alias('u')->where($condition)->count();
        if($count){
            $_P = new Page($count);
            $list = $_M->alias('u')->field('u.*, r.name as role_name')->join('__ROLE__ r ON r.id = u.role_id')->where($condition)->limit($_P->firstRow.','.$_P->listRows)->order($this->_aOrderBy)->select();
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        $this->_getFilterRoles();
        
        $this->assign('s_opts',array(
            'rid' => $rid,
            'keyword' => $keyword
        ));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    
    public function add(){
        $this->_getFilterRoles();
        $role_id = I('get.rid',0,'intval');
        $this->assign('role_id', $role_id);
        
        $this->display();
    }
    
    public function _before_insert(){
        $this->_checkRole();
    }
    public function _before_update(){
        $this->_checkRole();
    }
    
    /**
     * 非超级管理员时，检查角色是否为当前管理员直系下级
     */
    protected function _checkRole(){
        if(!session(C('ADMIN_AUTH_KEY'))){
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            
            $role_id = I('post.role_id',0,'intval');
            $role = M('Role')->find($role_id);
            
            if(!$role || $role['pid'] != $auth_role_id){
                $this->error('角色不合法！');
            }
        }
    }
    /**
     * 检查用户是否属于当前管理员直系下级
     * @param type $data
     * @return boolean
     */
    protected function _checkUser($data){
        if(!session(C('ADMIN_AUTH_KEY'))){
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            $role = M('Role')->find($data['role_id']);
            if(!role || $role['pid'] != $auth_role_id){
                return false;
            }
        }
        return true;
    }
    protected function _trigger_edit($data){
        if(!$this->_checkUser($data)){
            $this->error('无法对该用户进行任何操作！');
        }
        $this->_getFilterRoles();
    }
    /*
     * 列出当前管理员的下级角色，如果是超级管理员，列出全部
     */
    protected function _getFilterRoles(){
        if(session(C('ADMIN_AUTH_KEY'))){
            $roles = M('Role')->order('route,sort desc,id')->select();
            $roles = list_to_level($roles);
        }else{
            $auth = session(C('USER_AUTH_KEY'));
            $auth_role_id = $auth['role_id'];
            $roles = M('Role')->field('id,name,1 as grade')->where(array('pid'=>$auth_role_id))->order('sort desc,id')->select();
        }
        $this->assign('roles', $roles);
    }

    public function editPwd(){
        parent::edit();
    }
    public function updatePwd(){
        $id = I('post.id',0,'intval');
        if($id<=0){
            $this->error('参数有误', cookie('redirectUrl'));
        }
        $data = M(CONTROLLER_NAME)->find($id);
        if(!$this->_checkUser($data)){
            $this->error('无法对该用户进行任何操作！');
        }
        $new_pwd = I('post.password','');
        if(!preg_match('/^\w{6,12}$/', $new_pwd)){
            $this->error('密码长度为6-12个由字母、数字、下划线组成的字符');
        }
        $_M = M(CONTROLLER_NAME);
        $_M->save(array(
            'id' => $id,
            'password' => crypt($new_pwd)
        ));
        redirect(cookie('redirectUrl'));
    }
    
}