<?php
namespace Admin\Controller;
class CouponController extends CommonController {
    
    public function index(){
        $condition = array();
        $rid = I('get.rid', 0, 'intval');
        if($rid<1){
            redirect(U('CouponRule/index'));
        }
        $condition['c.coupon_rule_id'] = $rid;
        //批次
        $batch = I('get.batch', 0, 'intval');
        if($batch > 0){
            $condition['c.batch'] = $batch;
        }
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->field("SQL_CALC_FOUND_ROWS c.*, m.account")->alias('c')->join("LEFT JOIN __MEMBER__ m ON m.id = c.object_id")->where($condition)->limit($_P->firstRow.','.$_P->listRows)->order('id')->select();
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        
        $this->assign('coupon_rule', M('CouponRule')->find($rid));
        
        $this->assign('opts', array(
            'rid' => $rid,
            'batch' => $batch
        ));
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
}