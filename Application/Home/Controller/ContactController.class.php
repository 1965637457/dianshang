<?php
namespace Home\Controller;
class ContactController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_red');
        $this->assign('top_banner', $this->_getAdvert(6, true));
    }
    public function index(){
        
        $this->display();
    }
    
    public function _empty($action){
        
        $this->_getRewriteArticle($action);
        
        $this->display('detail');
    }

    public function detail(){
        $this->_getArticleById();
        
        $this->display('detail');
    }
    
    public function dofeedback(){
        $first_name = I('post.first_name', '', 'trim,htmlspecialchars');
        $last_name = I('post.last_name', '', 'trim,htmlspecialchars');
        $company = I('post.company', '', 'trim,htmlspecialchars');
        $address = I('post.address', '', 'trim,htmlspecialchars');
        $email = I('post.email', '', 'trim,htmlspecialchars');
        $memo = I('post.memo', '', 'trim,htmlspecialchars');
        if(empty($first_name) || empty($last_name) || empty($company) || empty($address) || !do_is_email($email) || empty($memo)){
            $this->error('Please fill your infos!');exit;
        }
        $result = M('Message')->add(array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'company' => $company,
            'address' => $address,
            'email' => $email,
            'memo' => $memo,
            'ip_addr' => get_client_ip(),
            'create_time' => NOW_TIME
        ));
        if(!$result){
            $this->error('Failure sending the message!');exit;
        }else{
            $this->success('Success! Thank you!', '', 3);exit;
        }
    }
}