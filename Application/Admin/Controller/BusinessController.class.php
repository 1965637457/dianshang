<?php
namespace Admin\Controller;
class BusinessController extends CommonController {
    
    public function index(){
        $level_id = I('get.level_id', -1, 'intval');
        $kw = I('get.kw', '');
        $condition = array("m.level_id = ml.id AND m.id = mi.member_id");
        if($level_id != -1){
            $condition[] = "m.level_id = {$level_id}";
        }
        if(!empty($kw)){
            $condition[] = "(m.account like '%".addslashes($kw)."%' OR m.username like '%".addslashes($kw)."%')";
        }
        $sql_where = implode(' AND ', $condition);
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->query("
            SELECT SQL_CALC_FOUND_ROWS *
            FROM __TABLE__
            ORDER BY id desc
            LIMIT {$_P->firstRow} , {$_P->listRows}
        ");
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        
        $this->assign('level_list', M('MemberLevel')->order('id')->select());
        $this->assign('opts', array(
            'level_id' => $level_id,
            'kw' => $kw
        ));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    public function _before_add(){
      //  $this->assign('level_list', M('MemberLevel')->order('id')->select());
    }
    
    protected function _trigger_edit(&$data){
    	$business_id = $data['id'];
    	$Model = M(); 
    	$sqlBusiness='
        		SELECT * 
				FROM '.C("DB_PREFIX").'goods AS g, 
 					 '.C("DB_PREFIX").'product AS p, 
					 '.C("DB_PREFIX").'category AS c 
				WHERE p.goods_id = g.id
  				AND g.category_id = c.id
			    AND p.stock != 0
  				AND g.business_id =
         	 	'.$business_id;
  
         $data['_business_list'] = $Model->query($sqlBusiness); 
    
         $this->assign('region_list', M('Region')->getField('id,region_name'));
    }
    
    public function insert(){
        $data_business = I('post.business', array());
    
        $_B = M('business');
        
        $business_id = $_B->add($data_business);
        if(!$business_id){
            $this->error('添加失败！'.$_B->getError());
        }
         redirect(U('index'));
    } 
    
  	public function update(){
  	  	 if(!IS_POST)exit(); 
  	  	 $data = I('post.business');
  	  	 $data['id'] = I('post.id');
  	  	 $_M = M(CONTROLLER_NAME);
  	  	 if($_M->save($data)){
	  	  	 if(cookie('redirectUrl')){
	  	  	 	redirect(cookie('redirectUrl'));
	  	  	 }else{
	  	  	 	redirect(U('index'));
	  	  	 }
  	  	 } else {
  	  	 	$this->error('操作失败！！', cookie('redirectUrl'));
  	  	 }
  	  	 
  	}
}