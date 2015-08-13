<?php

namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller{
    
    public function login(){
        if ( !session(C('USER_AUTH_KEY')) ) {
            $this->display();
        } else {
            redirect(U('Index/index'));
        }
    }
    protected function _check_verify($code, $id){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    public function dologin(){
        $account = I('post.account','');
        $password = I('post.pwd','');
        $verify = I('post.verify','');
        
        if(!$this->_check_verify($verify, 1)){
            $this->error('验证码错误，请重新输入');
        }
        empty($account) && $this->error('用户名不能为空！');
        empty($password) && $this->error('密码不能为空！');
        
        //生成认证条件
        $map = array(
            'account' => $account
        );
        $_User = M('User');
        $authInfo = $_User->where($map)->find();

        if (!$authInfo || $authInfo['status'] != 1) {
            $this->error('用户不存在或已禁用！');
        }else{
            if ($authInfo['password'] != crypt($password,$authInfo['password'])) {
                $this->error('密码错误！');
            }
            //保存登录信息
            $data = array(
                'id' => $authInfo['id'],
                'last_login_time' => NOW_TIME,
                'login_count' => array('exp','login_count+1'),
                'last_login_ip' => get_client_ip()
            );
            $_User->save($data);
            //记录SESSION
            $auth = array(
                'id' => $authInfo['id'],
                'account' => $authInfo['account'],
                'role_id' => $authInfo['role_id'],
                'last_login_time' => $authInfo['last_login_time']
            );
            session(C('USER_AUTH_KEY'), $auth);
            //管理员角色
            if( $authInfo['is_super'] == 1 ) {
                session(C('ADMIN_AUTH_KEY'), true);
            }
            // 缓存访问权限
           \Org\Util\RBAC::saveAccessList();
            $this->success('登录成功',U('Index/index'));
//            exit('<script>self.parent.location="'.U('Index/index').'"</script>');
        }
    }
    public function logout(){
        session(C('USER_AUTH_KEY'), NULL);
        session(C('ADMIN_AUTH_KEY'), NULL);
        redirect(U('login'));
    }
    
    public function verify(){
        $verify = new \Think\Verify(array('codeSet'=>'0123456789','fontSize'=>16,'imageH'=>30,'imageW'=>120,'length'=>4));
        $verify->simple(1);
    }
}

?>
