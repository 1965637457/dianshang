<?php
namespace Admin\Controller;
class MemberAddressController extends CommonController {
    
    public function add(){
        $member_id = I('get.mid', 0, 'intval');
        if($member_id < 1){
            $this->error('出错了！请刷新后再添加！');
        }
        $this->assign('member_id', $member_id);
        $this->assign('province_list', M('Region')->where(array('pid'=>0))->order('sort desc,id')->select());
        
        $this->display();
    }
    protected function _trigger_insert($id){
        $this->display();
        exit;
    }
    protected function _trigger_edit(&$data){
        
        $_Region = M('Region');
        $this->assign('province_list', $_Region->where(array('pid'=>0))->order('sort desc,id')->select());
        if($data['province_id'] > 0){
            $this->assign('city_list', $_Region->where(array('pid'=>$data['province_id']))->order('sort desc,id')->select());
        }
        if($data['city_id'] > 0){
            $this->assign('zone_list', $_Region->where(array('pid'=>$data['city_id']))->order('sort desc,id')->select());
        }
    }
    protected function _trigger_update(){
        $this->display();
        exit;
    }
    /**
     * 加载省、市、区的数据
     */
    public function getregion(){
        $pid = I('post.pid', 0, 'intval');
        if($pid<1){
            $this->error('出错了！请刷新后再试！');
        }
        $list = M('Region')->field("id,region_name as region")->where(array('pid'=>$pid))->order('sort desc,id')->select();
        if(!$list){
            $this->error('找不到数据！');
        }
        $this->ajaxReturn(array(
            'status' => 1,
            'list' => $list
        ));
    }
    /**
     * AJAX加载省市区三个下拉框
     */
    public function ajaxreloadregion(){
        $province_id = I('post.province_id', 0, 'intval');
        $city_id = I('post.city_id', 0, 'intval');
        $zone_id = I('post.zone_id', 0, 'intval');
        
        $_Region = M('Region');
        $this->assign('province_list', $_Region->where(array('pid'=>0))->order('sort desc,id')->select());
        if($province_id > 0){
            $this->assign('city_list', $_Region->where(array('pid'=>$province_id))->order('sort desc,id')->select());
        }
        if($city_id > 0){
            $this->assign('zone_list', $_Region->where(array('pid'=>$city_id))->order('sort desc,id')->select());
        }
        $this->assign('province_id', $province_id);
        $this->assign('city_id', $city_id);
        $this->assign('zone_id', $zone_id);
        
        $this->ajaxReturn(array(
            'status' => 1,
            'html' => $this->fetch()
        ));
    }
    public function reloadaddress(){
        $member_id = I('get.mid', 0, 'intval');
        if($member_id < 1){
            $this->error('出错了！请刷新后再添加！');
        }
        $list = M(CONTROLLER_NAME)->where(array('member_id'=>$member_id))->order('is_default desc, id')->select();
        $this->assign('list', $list);
        $this->assign('region_list', M('Region')->getField('id,region_name'));
        $this->ajaxReturn(array(
            'status' => 1,
            'html' => $this->fetch()
        ));
    }
    
}