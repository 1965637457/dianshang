<?php
namespace Admin\Controller;
class MemberController extends CommonController {
    
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
            SELECT SQL_CALC_FOUND_ROWS m.id, m.account, m.username, m.status, ml.member_level, mi.register_time
            FROM __TABLE__ m, __MEMBER_LEVEL__ ml, __MEMBER_INFO__ mi
            WHERE {$sql_where}
            ORDER BY m.id desc
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
        $this->assign('level_list', M('MemberLevel')->order('id')->select());
    }
    public function insert(){
        $data_main = I('post.main', array());
        if(!$data_main['level_id']){
            $this->error("会员等级必填！！！");
        }
        $_M = D(CONTROLLER_NAME);
        
        $member_id = $_M->insert($data_main);
        if(!$member_id){
            $this->error('添加失败！'.$_M->getError());
        }
        $data_info = I('post.info', array());
        $_Info = M('MemberInfo');
        $data_info['member_id'] = $member_id;
        $data_info['register_time'] = NOW_TIME;
        $data_info['register_ip'] = get_client_ip();
        $data_info['active_time'] = NOW_TIME;
        $_Info->add($data_info);
        
        redirect(U('index'));
    }
    protected function _trigger_edit(&$data){
        $member_id = $data['id'];
        $data['_info'] = M('MemberInfo')->find($member_id);
        $data['_address_list'] = M('MemberAddress')->where(array('member_id'=>$member_id))->order('is_default desc,id')->select();
        $Model = M();
    	 $sqlBuy='
        		SELECT * 
				FROM '.C("DB_PREFIX").'member_goods AS mg, 
					 '.C("DB_PREFIX").'product AS p, 
					 '.C("DB_PREFIX").'member_address AS ma, 
					 '.C("DB_PREFIX").'goods AS g, 
					 '.C("DB_PREFIX").'category AS c
				WHERE mg.product_id = p.product_id
				AND p.goods_id = g.id
				AND mg.member_id = ma.member_id
				AND mg.address_id = ma.id
				AND g.category_id = c.id
				AND mg.is_cart =0
				AND mg.member_id =
         	 	'.$member_id;
    	 
        $data['_bug_list'] = $Model->query($sqlBuy); 
     	$sqlStock='
        		SELECT * 
				FROM '.C("DB_PREFIX").'member_goods AS mg, 
					 '.C("DB_PREFIX").'product AS p,  
					 '.C("DB_PREFIX").'goods AS g, 
					 '.C("DB_PREFIX").'category AS c,
					 '.C("DB_PREFIX").'user AS u
				WHERE mg.product_id = p.product_id
				AND p.goods_id = g.id 
				AND g.category_id = c.id
				AND mg.member_id = u.id
				AND mg.is_cart = 1
				AND mg.member_id =
         	 	'.$member_id;
    	
        $data['_stock_list'] = $Model->query($sqlStock);
        $this->assign('level_list', M('MemberLevel')->order('id')->select());
        $this->assign('region_list', M('Region')->getField('id,region_name'));
    }
    public function modifypwd(){
        $id = I('get.id', 0, 'intval');
        if($id<=0){
            $this->error('参数有误');
        }
        $data = M(CONTROLLER_NAME)->field('id,account')->find($id);
        $this->assign('data', $data);
        
        $this->display();
    }
    public function updatepwd(){
        $id = I('post.id',0,'intval');
        if($id<=0){
            $this->error('参数有误', cookie('redirectUrl'));
        }
        $_M = M(CONTROLLER_NAME);
        $data = $_M->find($id);
        $new_pwd = I('post.password','');
        if(!preg_match('/^\w{6,12}$/', $new_pwd)){
            $this->error('密码长度为6-12个由字母、数字、下划线组成的字符');
        }
        $_M->save(array(
            'id' => $id,
            'password' => crypt($new_pwd)
        ));
        redirect(cookie('redirectUrl'));
    }

    protected function _trigger_delete($ids){
        M('MemberInfo')->where(array('goods_id'=>array('in', $ids)))->delete();
    }
}