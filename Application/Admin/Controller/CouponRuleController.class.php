<?php
namespace Admin\Controller;
class CouponRuleController extends CommonController {
    
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->field("SQL_CALC_FOUND_ROWS *")->where($this->_aWhere)->limit($_P->firstRow.','.$_P->listRows)->order($this->_aOrderBy)->select();
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        
        $this->assign('coupon_types', C('COUPON_CONFIG.COUPON_TYPE'));
        $this->assign('target_types', C('COUPON_CONFIG.TARGET_TYPE'));
        $this->assign('limit_types', C('COUPON_CONFIG.LIMIT_TYPE'));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    public function add(){
        $this->assign('coupon_types', C('COUPON_CONFIG.COUPON_TYPE'));
        $this->assign('target_types', C('COUPON_CONFIG.TARGET_TYPE'));
        $this->assign('limit_types', C('COUPON_CONFIG.LIMIT_TYPE'));
        $this->assign('member_levels', M('MemberLevel')->select());
        $this->assign('category_list', $this->_getListToLevel('Category'));
        
        $this->display();
    }
    public function insert(){
        $data_main = I('post.main', array());
        $data_limit_members = I('post.limit_members', array());
        $data_limit_levels = I('post.limit_levels', array());
        $data_limit_products = I('post.limit_products', array());
        $data_limit_category = I('post.limit_category', array());
        $target_type = $data_main['target_type'];
        $limit_type = $data_main['limit_type'];
        if(($target_type==2 && empty($data_limit_members)) || ($target_type==3 && empty($data_limit_levels)) || ($limit_type==2 && empty($data_limit_products)) || ($limit_type==3 && empty($data_limit_category))){
            $this->error('内容不完整！');
        }
        if($target_type==2){
            $data_main['target_object'] = implode(',', $data_limit_members);
        }
        if($target_type==3){
            $data_main['target_object'] = implode(',', $data_limit_levels);
        }
        $_M = D(CONTROLLER_NAME);
        $id = $_M->insert($data_main);
        if(!$id){
            $this->error('添加失败！');
        }
        $_Limitation = M('CouponLimitation');
        if($limit_type == 2) {
            $list = $data_limit_products;
        }elseif($limit_type == 3){
            $list = $data_limit_category;
        }
        if($list){
            $dataList = array();
            foreach ($list as $object_id){
                $dataList[] = array(
                    'coupon_rule_id' => $id,
                    'limit_type' => $limit_type,
                    'object_id' => $object_id
                );
            }
            $_Limitation->addAll($dataList);
        }
        redirect(U('index'));
    }
    protected function _trigger_edit(&$data){
        $target_type = $data['target_type'];
        if($target_type == 2){
            $data['_member_list'] = M('Member')->field('id,account')->where(array('id'=>array('in',$data['target_object'])))->select();
        }
        if($target_type == 3){
            $data['_chosen_member_level'] = $data['target_object'];
        }
        $limit_type = $data['limit_type'];
        if($limit_type == 2){
            $data['_product_list'] = M('CouponLimitation')->alias('pl')->field("g.id, g.goods, g.goods_sno")->join("LEFT JOIN __GOODS__ g ON g.id = pl.object_id")->where(array('coupon_rule_id'=>$data['id']))->select();
        }
        if($limit_type == 3){
            $data['_chosen_category'] = M('CouponLimitation')->where(array('coupon_rule_id'=>$data['id']))->getField("GROUP_CONCAT(object_id)");
        }
        $this->assign('member_levels', M('MemberLevel')->select());
        $this->assign('category_list', $this->_getListToLevel('Category'));
        $this->assign('coupon_types', C('COUPON_CONFIG.COUPON_TYPE'));
        $this->assign('target_types', C('COUPON_CONFIG.TARGET_TYPE'));
        $this->assign('limit_types', C('COUPON_CONFIG.LIMIT_TYPE'));
    }
    public function update(){
        $id = I('post.id', 0, 'intval');
        if($id < 1){
            $this->error('参数错误！');
        }
        $data_main = I('post.main', array());
        $data_limit_members = I('post.limit_members', array());
        $data_limit_levels = I('post.limit_levels', array());
        $data_limit_products = I('post.limit_products', array());
        $data_limit_category = I('post.limit_category', array());
        $target_type = $data_main['target_type'];
        $limit_type = $data_main['limit_type'];
        if(($target_type==2 && empty($data_limit_members)) || ($target_type==3 && empty($data_limit_levels)) || ($limit_type==2 && empty($data_limit_products)) || ($limit_type==3 && empty($data_limit_category))){
            $this->error('内容不完整！');
        }
        if($target_type==2){
            $data_main['target_object'] = implode(',', $data_limit_members);
        }
        if($target_type==3){
            $data_main['target_object'] = implode(',', $data_limit_levels);
        }
        $data_main['id'] = $id;
        $_M = D(CONTROLLER_NAME);
        $result = $_M->update($data_main);
        if(false === $result){
            $this->error('更新失败！');
        }
        $_Limitation = M('CouponLimitation');
        $_Limitation->where(array('coupon_rule_id' => $id))->delete();
        if($limit_type == 2) {
            $list = $data_limit_products;
        }elseif($limit_type == 3){
            $list = $data_limit_category;
        }
        if($list){
            $dataList = array();
            foreach ($list as $object_id){
                $dataList[] = array(
                    'coupon_rule_id' => $id,
                    'limit_type' => $limit_type,
                    'object_id' => $object_id
                );
            }
            $_Limitation->addAll($dataList);
        }
        redirect(U('index'));
    }
    protected function _trigger_delete($ids){
        M('Coupon')->where(array('coupon_rule_id' => array('in', $ids)))->delete();
        M('CouponLimitation')->where(array('coupon_rule_id' => array('in', $ids)))->delete();
    }
    /**
     * 发放优惠券
     */
    public function grant(){
        $id = I('get.id', 0, 'intval');
        $_M = M(CONTROLLER_NAME);
        $data = $_M->find($id);
        if(!$data){
            $this->error('数据出错了！');
        }
        $target_type = $data['target_type'];
        if($target_type == 2){
            $data['_member_list'] = M('Member')->field('id,account')->where(array('id'=>array('in',$data['target_object'])))->select();
        }
        if($target_type == 3){
            $data['_chosen_member_level'] = $data['target_object'];
        }
        $limit_type = $data['limit_type'];
        if($limit_type == 2){
            $data['_product_list'] = M('CouponLimitation')->alias('pl')->field("g.id, g.goods, g.goods_sno")->join("LEFT JOIN __GOODS__ g ON g.id = pl.object_id")->where(array('coupon_rule_id'=>$data['id']))->select();
        }
        if($limit_type == 3){
            $data['_chosen_category'] = M('CouponLimitation')->where(array('coupon_rule_id'=>$data['id']))->getField("GROUP_CONCAT(object_id)");
        }
        $this->assign('member_levels', M('MemberLevel')->select());
        $this->assign('category_list', $this->_getListToLevel('Category'));
        $this->assign('coupon_types', C('COUPON_CONFIG.COUPON_TYPE'));
        $this->assign('target_types', C('COUPON_CONFIG.TARGET_TYPE'));
        $this->assign('limit_types', C('COUPON_CONFIG.LIMIT_TYPE'));
        $this->assign('data', $data);
        $this->display();
    }
    public function createcoupon(){
        $id = I('post.id', 0, 'intval');
        $amount = I('post.amount', 1, 'intval');
        //调取优惠券规则信息
        $_Rule = M('CouponRule');
        $rule = $_Rule->find($id);
        if(!$rule){
            $this->error('该优惠券不存在！');
        }
        //使用时间是否有效
        if(NOW_TIME > $rule['end_time']){
            $this->error('该优惠券已经过期！');
        }
        //生成批次
        $batch = $rule['total_batch'] + 1;
        $_Rule->save(array(
            'id' => $id,
            'total_batch' => $batch
        ));
        //
        $_Coupon = D('Coupon');
        $dataList = array();
        switch ($rule['target_type']) {
            case 4:
                //通用优惠券，生成规则是字母+数字
                for($i = 0; $i < $amount; $i++){
                    $coupon = $_Coupon->generateCoupon(2);
                    $dataList[] = array(
                        'coupon_rule_id' => $id,
                        'coupon' => $coupon,
                        'object_id' => 0,
                        'batch' => $batch,
                        'status' => 1,
                        'use_time' => 0
                    );
                }
                break;
            case 1:
                //全体会员
                $members = M('Member')->field('id')->where(array('status'=>1))->select();
                if($members){
                    $coupon = $_Coupon->generateCoupon(1);
                    foreach ($members as $member){
                        $dataList[] = array(
                            'coupon_rule_id' => $id,
                            'coupon' => $coupon,
                            'object_id' => $member['id'],
                            'batch' => $batch,
                            'status' => 1,
                            'use_time' => 0
                        );
                        $coupon++;
                    }
                }
                break;
            case 2:
                //指定会员
                $members = M('Member')->field('id')->where(array('status'=>1,'id'=>array('in', $rule['target_object'])))->select();
                if($members){
                    $coupon = $_Coupon->generateCoupon(1);
                    foreach ($members as $member){
                        $dataList[] = array(
                            'coupon_rule_id' => $id,
                            'coupon' => $coupon,
                            'object_id' => $member['id'],
                            'batch' => $batch,
                            'status' => 1,
                            'use_time' => 0
                        );
                        $coupon++;
                    }
                }
                break;
            case 3:
                //指定会员等级
                $members = M('Member')->field('id')->where(array('status'=>1,'level_id'=>array('in', $rule['target_object'])))->select();
                if($members){
                    $coupon = $_Coupon->generateCoupon(1);
                    foreach ($members as $member){
                        $dataList[] = array(
                            'coupon_rule_id' => $id,
                            'coupon' => $coupon,
                            'object_id' => $member['id'],
                            'batch' => $batch,
                            'status' => 1,
                            'use_time' => 0
                        );
                        $coupon++;
                    }
                }
                break;
        }
        if(!empty($dataList)){
            $_Coupon->addAll($dataList);
        }
        redirect(U('Coupon/index',array('rid'=>$id)));
    }

    /**
     * 调取会员列表
     */
    public function members(){
        $level_id = I('get.level_id', -1, 'intval');
        $kw = I('get.kw', '');
        $condition = array(
            "m.level_id = ml.id AND m.id = mi.member_id"
        );
        if($level_id != -1){
            $condition[] = "m.level_id = {$level_id}";
        }
        if(!empty($kw)){
            $condition[] = "(m.account like '%".addslashes($kw)."%' OR m.username like '%".addslashes($kw)."%')";
        }
        $sql_where = implode(' AND ', $condition);
        $_M = M('Member');
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
        $this->display();
    }
    /**
     * 调取产品
     */
    public function products(){
        $category_id = I('get.category_id', 0, 'intval');
        $kw = I('get.kw', '');
        $condition = array(
            "g.category_id = c.id AND g.status = 1"
        );
        if($category_id > 0){
            $condition[] = "g.category_id = {$category_id}";
        }
        if(!empty($kw)){
            $condition[] = "g.goods like '%".addslashes($kw)."%'";
        }
        $sql_where = implode(' AND ', $condition);
        $_M = M('Goods');
        $_P = new \Think\Page(0);
        $list = $_M->query("
            SELECT SQL_CALC_FOUND_ROWS g.*, c.category
            FROM __TABLE__ g, __CATEGORY__ c
            WHERE {$sql_where}
            ORDER BY g.id
            LIMIT {$_P->firstRow} , {$_P->listRows}
        ");
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        $this->assign('categories', $this->_getListToLevel('Category'));
        $this->assign('opts', array(
            'category_id' => $category_id,
            'kw' => $kw
        ));
        $this->display();
    }
}