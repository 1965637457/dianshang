<?php
namespace Home\Controller;
class ProductController extends CommonController{
    //put your code here
    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_light_orange');
        $this->assign('top_banner', $this->_getAdvert(9, true));
    }
    
    public function index(){
        
        $this->display();
    }
    
    public function category(){
        $rewriteuri = I('get.cate_name', '');
        if(empty($rewriteuri)){
            $article = $this->_getDataById('ProductCategory');
        }else{
            $article = $this->_getRewriteData($rewriteuri, 'ProductCategory');
        }
        if(!$article || $article['status']!=1){
            redirect(U('index'));
        }
        $this->assign('article', $article);
        
        $_Product = M('Product');
        $condition = array(
            'cate_id' => $article['id'],
            'status' => 1
        );
        $list = $_Product->where($condition)->order('sort desc,id')->select();
        $this->assign('list', $list);
        
        $this->display();
    }
    
    public function detail(){
        $data = $this->_getDataById('Product');
        if(!$data || $data['status']!=1){
            redirect(U('index'));
        }
        $this->assign('data', $data);
        //SEO
        $meta_title = C('META_TITLE');
        C('META_TITLE', $data['title'].' - '.$meta_title);
        $data['keywords'] && C('META_KEYWORD', $data['keywords']);
        
        $article = M('ProductCategory')->find($data['cate_id']);
        $this->assign('article', $article);
//        dump($article);
        if($article['id']==3){
            $type = I('get.type','','trim');
            if($type=='view'){
                $this->display('detail_view');
            }else{
                $this->display('detail_third');
            }
        }else{
            $this->display();
        }
    }
    
    public function _empty($action){
        $data = $this->_getRewriteData($action, 'Product');
        if(!$data || $data['status']!=1){
            redirect(U('index'));
        }
        $this->assign('data', $data);
        //SEO
        $meta_title = C('META_TITLE');
        C('META_TITLE', $data['title'].' - '.$meta_title);
        $data['keywords'] && C('META_KEYWORD', $data['keywords']);
        
        $article = M('ProductCategory')->find($data['cate_id']);
        $this->assign('article', $article);
        
        if($article['id']==3){
            $type = I('get.type','','trim');
            if($type=='view'){
                $this->display('detail_view');
            }else{
                $this->display('detail_third');
            }
        }else{
            $this->display('detail');
        }
    }
    
    public function inquiry(){
        $product_id = I('get.id', 0, 'intval');
        $inquiry_type = I('get.type', 5, 'intval');
        if($product_id <= 0){
            $this->display('inquiry_fail');exit;
        }
        $product = M('Product')->find($product_id);
        if(!$product || $product['status']!=1){
            $this->display('inquiry_fail');exit;
        }
        $this->assign('product', $product);
        $this->assign('inquiry_type', $inquiry_type);
        
        $this->display();
    }
    
    public function doinquiry(){
        $product_id = I('post.product_id', 0, 'intval');
        $inquiry_type = I('post.inquiry_type', 5, 'intval');
        $first_name = I('post.first_name', '', 'trim,htmlspecialchars');
        $last_name = I('post.last_name', '', 'trim,htmlspecialchars');
        $company = I('post.company', '', 'trim,htmlspecialchars');
        $address = I('post.address', '', 'trim,htmlspecialchars');
        $email = I('post.email', '', 'trim,htmlspecialchars');
        $memo = I('post.memo', '', 'trim,htmlspecialchars');
        if(empty($first_name) || empty($last_name) || empty($company) || empty($address) || !do_is_email($email) || empty($memo)){
            $this->display('inquiry_fail');exit;
        }
        $result = M('Inquiry')->add(array(
            'product_id' => $product_id,
            'inquiry_type' => $inquiry_type,
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
            $this->display('inquiry_fail');exit;
        }else{
            $this->display('inquiry_success');exit;
        }
    }
}

?>
