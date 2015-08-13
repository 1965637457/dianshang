<?php
namespace Admin\Controller;
class ProfileController extends CommonController{

    /**
     * 基本资料
     */
    public function edit(){
        $auth = session(C('USER_AUTH_KEY'));
        $_User = M('User');
        $data = $_User->find($auth['id']);
        $this->assign('data', $data);
        
        $this->display();
    }
    
    /**
     * 更新基本资料
     */
    public function update(){
        $auth = session(C('USER_AUTH_KEY'));
        
        $company = I('post.company','');
        $contact = I('post.contact','');
        $fax = I('post.fax','');
        $phone1 = I('post.phone1','');
        $phone2 = I('post.phone2','');
        $bank_name = I('post.bank_name','');
        $bank_account = I('post.bank_account','');
        $tax_no = I('post.tax_no','');
        $address = I('post.address','');
        
        $_User = M('User');
        $data = array(
            'id' => $auth['id'],
            'company' => $company,
            'contact' => $contact,
            'fax' => $fax,
            'phone1' => $phone1,
            'phone2' => $phone2,
            'bank_name' => $bank_name,
            'bank_account' => $bank_account,
            'tax_no' => $tax_no,
            'address' => $address,
        );
        $_User->save($data);
        
        $this->redirect('edit');
    }
    /**
     * 修改密码
     */
    public function editPwd(){
        $auth = session(C('USER_AUTH_KEY'));
        $_User = M('User');
        $data = $_User->find($auth['id']);
        $this->assign('data', $data);
        
        $this->display();
    }
    
    /**
     * 更新密码
     */
    public function updatePwd(){
        $auth = session(C('USER_AUTH_KEY'));

        $old_password = I('post.old_password','');
        $new_password = I('post.new_password','');
        empty($old_password) && $this->error('旧密码不能为空');
        empty($new_password) && $this->error('新密码不能为空');
        
        $_User = M('User');
        $user = $_User->field('id,password')->find($auth['id']);
        !$user && $this->error('管理员已经不存在');
        if($user['password'] != crypt($old_password,$user['password'])){
            $this->error('您输入的旧密码不正确');
        }
        $user['password'] = crypt($new_password);
        $_User->save($user);
        $this->success('成功修改密码');
    }
}

?>
