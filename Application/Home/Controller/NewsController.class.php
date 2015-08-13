<?php
namespace Home\Controller;
class NewsController extends CommonController{

    public function _initialize() {
        parent::_initialize();
        $this->assign('left_side_bg', 'bg_light_green');
        $this->assign('top_banner', $this->_getAdvert(3, true));
    }
    public function index(){
        $data = $this->_getFirstData('NewsCategory');
        $this->assign('article', $data);
        
        if(empty($data['rewriteuri'])){
            redirect(U('News/category',array('id'=>$data['id'])));
        }else{
            redirect(U('News/'.$data['rewriteuri']));
        }
    }
    
    public function category(){
        $data = $this->_getDataById('NewsCategory');
        if(!$data || $data['status']!=1){
            $this->redirect('index');
        }
        $this->assign('article', $data);
        
        $_M = M(CONTROLLER_NAME);
        $this->_getList($_M, $data['id']);
        
        $this->display('news');
    }
    
    protected function _getList($_M, $cid=1){
        $condition = array('status'=>1,'cid'=>$cid);
        $count = $_M->where($condition)->count();
        $_P = new \Think\Page($count, 10);
        $_P->setConfig('prev', 'Previous');
        $_P->setConfig('next', 'Next');
        $list = $_M->where($condition)->order('publish_time desc,id desc')->limit($_P->firstRow.','.$_P->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page_nav', $_P->showNewsPage());
    }
//    protected function _getInfo($_M, $id, $redirect){
//        $data = $_M->find($id);
//        if(!$data || $data['status'] != 1){
//            $this->redirect($redirect);
//        }
//        $this->assign('data', $data);
//
//        //上一篇，下一篇
//        $this->assign('prev_id', $_M->where(array('cid'=>$data['cid'],'publish_time'=>array('gt',$data['publish_time'])))->order('publish_time,id')->limit(1)->getField('id'));
//        $this->assign('next_id', $_M->where(array('cid'=>$data['cid'],'publish_time'=>array('lt',$data['publish_time'])))->order('publish_time desc,id desc')->limit(1)->getField('id'));
//    }

    public function _empty($action){
        $data = $this->_getRewriteData($action, 'NewsCategory');
        if(!$data || $data['status']!=1){
            $this->redirect('index');
        }
        $this->assign('article', $data);
        
        $_M = M(CONTROLLER_NAME);
        $this->_getList($_M, $data['id']);
        
        $this->display('news');
    }
    
    public function detail(){
        //新闻内容
        $data = $this->_getDataById();
        if(!$data || $data['status']!=1){
            redirect('index');
        }
        $this->assign('data', $data);
        $_M = M('News');
        //上一篇，下一篇
        $this->assign('prev_id', $_M->where(array('cid'=>$data['cid'],'publish_time'=>array('gt',$data['publish_time'])))->order('publish_time,id')->limit(1)->getField('id'));
        $this->assign('next_id', $_M->where(array('cid'=>$data['cid'],'publish_time'=>array('lt',$data['publish_time'])))->order('publish_time desc,id desc')->limit(1)->getField('id'));
        //分类
        $this->assign('article', M('NewsCategory')->find($data['cid']));
        
        $this->display('detail');
    }
}

?>
