<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{

    protected $_aWhere = '';
    protected $_aId = 0;
    protected $_aOrderBy = 'id desc';

    public function _initialize(){
        //检查认证识别号
        if (!session(C('USER_AUTH_KEY'))) {
            redirect(C('USER_AUTH_GATEWAY'));
        }
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            if (!\Org\Util\RBAC::AccessDecision()) {
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    $this->error('无权限访问');
                }
            }
        }
        $auth = session(C('USER_AUTH_KEY'));
        $this->assign('auth', $auth);
        if(!IS_AJAX){
            //TODO：根据当前管理员列出所拥有的权限
            $_Node = M('Node');
            if(session(C('ADMIN_AUTH_KEY'))){
                $nodes = $_Node->where('status = 1')->order('route,sort desc,id')->select();
            }else{
                // 管理员拥有权限
                $nodes = $_Node->query("
                    SELECT a.* 
                    FROM __TABLE__ a 
                    WHERE a.status=1 and a.id in ( select node_id from smi_access where role_id ={$auth['role_id']} ) 
                    ORDER BY a.route,a.sort desc,a.id
                ");
            }
            $privileges = list_to_tree($nodes); 
            $this->assign('privileges', $privileges);
            $this->assign('node_title', 'title'.L('LANG_MARK'));
            //当前控制器名称
            $this->assign('bread_nav_ctrl', $_Node->where(array('name'=>CONTROLLER_NAME))->getField('title'.L('LANG_MARK')));
            //当前操作
            switch (ACTION_NAME) {
                case 'index':
                    $bread_nav_action = L('PAGE_LIST');
                    break;
                case 'search':
                    $bread_nav_action = L('PAGE_SEARCH');
                    break;
                case 'add':
                    $bread_nav_action = L('PAGE_ADD');
                    break;
                case 'edit':
                    $bread_nav_action = L('PAGE_EDIT');
                    break;
                case 'editPwd':
                    $bread_nav_action = L('PAGE_PWD');
                    break;
                case 'read':
                    $bread_nav_action = L('PAGE_READ');
                    break;
                case 'privilege':
                    $bread_nav_action = L('PAGE_PRIV');
                    break;
                default:
                    $bread_nav_action = '';
                    break;
            }
            $this->assign('bread_nav_action', $bread_nav_action);
        }
    }
    
    /**
     * 默认列表
     */
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->field("SQL_CALC_FOUND_ROWS *")->where($this->_aWhere)->limit($_P->firstRow.','.$_P->listRows)->order($this->_aOrderBy)->select();
        $result = $_M->query("SELECT FOUND_ROWS() as count"); //SELECT FOUND_ROWS() 的用法是？？？？    query与手册的用法不同$Model->query("select * from think_user where status=1");
        $_P->totalRows = $result[0]['count']; 

        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    public function search(){
        $condition = array();
        $kw = I('get.kw','');
        if(!empty($kw)){
            $condition['title'] = array('like',"%{$kw}%");
        }
        
        $_M = M(CONTROLLER_NAME);
        $count = $_M->where($condition)->count();
        $list = array();
        if($count){
            $_P = new \Think\Page($count);
            $list = $_M->where($condition)->limit($_P->firstRow.','.$_P->listRows)->order('id')->select();
            $this->assign('page_nav', $_P->show());
        }
        $this->assign('list', $list);
        $this->assign('opts',array(
            'kw' => $kw
        ));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    /**
     * 树型列表
     */
    protected function _tree(){
        $_M = M(CONTROLLER_NAME);
        $dataset = $_M->where($this->_aWhere)->order('route,sort desc,id')->select();
        $list = list_to_level($dataset);
        $this->assign('list', $list);
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    /**
     * 批处理
     */
    public function handle(){
        $handle_type = I('post.handle_type','');
        if(!$handle_type){
            $this->error('没有相应的操作', cookie('redirectUrl'), 2);
        }
        //检查权限
        if (!\Org\Util\RBAC::AccessDecision($handle_type)) {
            $this->error('无相关权限，请联系管理员','',2);
        }
        //执行相应操作
        $handle_action = '_handle_'.$handle_type;
        if(method_exists($this, $handle_action)){
            $this->$handle_action();exit;
        }
        $this->error('暂不支持此操作！'.$handle_action, cookie('redirectUrl'), 5);
    }
    protected function _handle_delete(){
        $this->delete();
    }
    protected function _handle_recycle(){
        $this->recycle();
    }
//    protected function _handle_sort(){
//        $sorts = I('post.sort','');
//        !is_array($sorts) && $this->error('参数有误', cookie('redirectUrl'), 2);
//        empty($sorts) && $this->error('参数有误', cookie('redirectUrl'), 2);
//        $_M = D(CONTROLLER_NAME);
//        foreach($sorts as $id => $sort){
//            $_M->save(array(
//                'id' => $id,
//                'sort' => $sort
//            ));
//        }
//        // 回调接口
//        if (method_exists($this, '_trigger_sort')) {
//            $this->_trigger_sort();
//        }
//        if(IS_AJAX){
//            $this->success('删除成功');
//        }else{
//            redirect(cookie('redirectUrl'));
//        }
//    }
    public function _handle_sort(){
        $id = I('post.id', 0, 'intval');
        $val = I('post.val', 0, 'intval');
        $id < 1 && $this->error('参数有误', cookie('redirectUrl'), 2);
        $_M = D(CONTROLLER_NAME);
        $result = $_M->save(array(
            'id' => $id,
            'sort' => $val
        ));
        if(false === $result){
            $this->error('排序失败', cookie('redirectUrl'), 2);
        }
        // 回调接口
        if (method_exists($this, '_trigger_sort')) {
            $this->_trigger_sort();
        }
        $this->success('成功更新排序', cookie('redirectUrl'), 2);
    }
    public function add(){
    	
        $this->display();
    }
    public function insert(){
        $_M = D(CONTROLLER_NAME);
        $result = $_M->insert();
        if($result){
            if(method_exists($this, '_trigger_insert')){
                $this->_trigger_insert($result);
            }
            //成功提示
            if(cookie('redirectUrl')){
                redirect(cookie('redirectUrl'));
            }else{
                redirect(U('index'));
            }
        } else {
            //失败提示
            $this->error('添加失败！'.$_M->getError(), '', 3);
        }
    }
    
    public function edit(){
        $id = I('get.id',0,'intval');
        if($id<=0){
            $this->error('参数有误', cookie('redirectUrl'));
        }
        $_M = D(CONTROLLER_NAME);
        $data = $_M->find($id);
 
        if(!$data){
            $this->error('数据不存在', cookie('redirectUrl'));
        }
        if(method_exists($this, '_trigger_edit')){
            $this->_trigger_edit($data);
        } 
        $this->assign('data', $data);
        $this->display();
    }
    public function update(){
        $_M = D(CONTROLLER_NAME);
 
        if(false !== $_M->update()){
            if (method_exists($this, '_trigger_update')) {
                $this->_trigger_update();
            }
            //成功提示
            if(cookie('redirectUrl')){
                redirect(cookie('redirectUrl'));
            }else{
                redirect(U('index'));
            }
        } else {
            //失败提示
            $this->error('更新失败！'.$_M->getError(), '', 3);
        }
    }
    
    /**
     * 设置状态
     */
    public function setStatus(){
        $id = I('get.id',0,'intval');
        $field = I('get.field','');
        $val = I('get.val',0,'intval');
        if($id <= 0 || $field===''){
            $this->error('参数有误', cookie('redirectUrl'));
        }
        $val = $val > 0 ? 0 : 1;
        $text = $val === 1 ? '启用' : '禁用';
        $class = 'status-'.$val;
        $_M = D(CONTROLLER_NAME);
        $result = $_M->save(array(
            'id' => $id,
            $field => $val
        ));
        if($result){
            if(IS_AJAX){
                $this->ajaxReturn(array(
                    'status' => 1,
                    'text' => $text,
                    'className' => $class,
                    'url' => U('setStatus',array('id'=>$id,'field'=>$field,'val'=>$val)),
                    'val' => $val,
                    'info' => '更新完成',
                ));
            }else{
                redirect(cookie('redirectUrl'));
            }
        }else{
            $this->error('更新失败', cookie('redirectUrl'));
        }
    }
    public function delete(){
        $_M = D(CONTROLLER_NAME);
        $id = I('id','');
        empty($id) && $this->error('参数有误', cookie('redirectUrl'), 2);
        $ids = is_array($id) ? implode(',', $id) : $id;
        $result = $_M->delete($ids);
        if($result){
            if(method_exists($this, '_trigger_delete')) {
                $this->_trigger_delete($ids);
            }
            if(IS_AJAX){
                $this->success('删除成功');
            }else{
                redirect(cookie('redirectUrl'));
            }
        }else{
            $this->error('删除失败',  cookie('redirectUrl'), 2);
        }
    }
    protected function _deleteTree() {
        $_M = D(CONTROLLER_NAME);
        $id = I('id','');
        empty($id) && $this->error('参数有误', cookie('redirectUrl'), 2);
        $condition = array(
            'id' => array('in', $id)
        );
        $dataset = $_M->where($condition)->select();
        if(!$dataset){
            $this->error('删除失败',  cookie('redirectUrl'), 2);
        }
        foreach($dataset as $vo){
            $route_like = $vo['route'] == '' ? $vo['id'] : array($vo['route'].','.$vo['id'].',%', $vo['route'].','.$vo['id']);
            $condition = array(
                'id' => $vo['id'],
                'route' => array('like',$route_like,'OR'),
                '_logic' => 'OR'
            );
            $_M->where($condition)->delete();
        }
        if(IS_AJAX){
            $this->ajaxReturn(array(
                'status' => 1,
                'info' => 'refresh'
            ));
        }else{
            redirect(cookie('redirectUrl'));
        }
    }
    /**
     * 取得层级列表
     * @param type $model 表名
     * @return type
     */
    protected function _getListToLevel($model){
        $dataset = M($model)->order('route,sort desc,id')->select();
        if(!$dataset){
            return array();
        }else{
            return list_to_level($dataset);
        }
    }
    
    
}

?>
