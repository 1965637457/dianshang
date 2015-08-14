<?php
namespace Home\Controller;
/**
*  处理会员登录与注册
*/
class PublicController extends CommonController
{


 	//会员登录界面展示
    public function login(){
        if ( !session(C('USER_AUTH_KEY')) ) {
            $this->display();
        } else {
            redirect(U('Index/index'));
        }
    }

    //会员登录界面
    public function  dologin(){        

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
        $_Member = M('member');
        $authInfo = $_Member->where($map)->find();

        if (!$authInfo || $authInfo['status'] != 1) {
            $this->error('用户不存在或已禁用！');
        }else{
            if ($authInfo['pwd'] != crypt($password,$authInfo['password'])) {
                $this->error('密码错误！');
            }
            //保存登录信息
            $data = array(
                'member_id' => $authInfo['id'],
                'login_time' => NOW_TIME,
                'login_ip' => get_client_ip()
            );
            $_Member_info = M('Member_info');
            $_Member_info->save($data);
            //记录SESSION
            $auth = array(
                'id' => $authInfo['id'],
                'account' => $authInfo['account'],
                //'role_id' => $authInfo['role_id'],
                'login_time' => NOW_TIME
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

	//会员注册
	public function register(){

		$data = I("post.register");
		$_M = M('member');
		if($_M->add($data)){
			$this->success('注册成功！！！',U('login'));
		}

	}

	//检验验证码
  	protected function _check_verify($code, $id){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
	 
	//生成验证码
 	public function verify(){
        $verify = new \Think\Verify(array('codeSet'=>'0123456789','fontSize'=>16,'imageH'=>30,'imageW'=>120,'length'=>4));
        $verify->simple(1);
    }

    //退出登录
    public function logOut(){
        //保存会员的登录时间
        $auth = session(C('ADMIN_AUTH_KEY'));
        $_Member_info = M('member_info');
        $memberInfo = $_Member_info->find($auth['id']);
        $memberInfo["active_time"] = $memberInfo['active_time'] + NOW_TIME - $auth['login_time'];
        $_Member_info->save($memberInfo);
        session_destroy();

    }
}