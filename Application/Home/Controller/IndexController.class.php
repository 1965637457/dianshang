<?php
namespace Home\Controller;
class IndexController extends CommonController {
    
    public function index(){
        //快速链接
//        $config_quick_link = C('INDEX_QUICK_LINK');
//        if($config_quick_link){
//            $quick_links  = array();
//            $array = preg_split('/[,;\r\n]+/', trim($config_quick_link, ",;\r\n"));
//            if(strpos($config_quick_link,'|')){
//                foreach ($array as $val) {
//                    list($title, $link) = explode('|', $val);
//                    $quick_links[trim($title)]   = trim($link);
//                }
//            }
//            $this->assign('quick_links', $quick_links);
//        }
        //新闻
        $news = M('News')->where(array('status'=>1))->order('publish_time desc,id')->limit(3)->select();
        $this->assign('news', $news);
        //展会形象banner
        $this->assign('index_banners', $this->_getAdvert(1));
        
        
        $this->display();
    }
    
    public function subscribe(){
        $email = I('post.email', '', 'htmlspecialchars');
        if(!do_is_email($email)){
            $this->error('Please fill your email!');
        }
        M('Subscription')->add(array(
            'email' => $email,
            'create_time' => NOW_TIME
        ));
        $this->success('Success to subscribe!');
    }
    
}