<?php
namespace Admin\Controller;
class PromotionController extends CommonController {
    
    public function insert(){
        $data = I('post.main', array());
        $_M = D(CONTROLLER_NAME);
        $id = $_M->insert($data);
        if($id){
            redirect(U('edit',array('id'=>$id)));
        } else {
            //失败提示
            $this->error('添加失败！'.$_M->getError(), '', 3);
        }
    }
    protected function _trigger_edit(&$data){
        $promotion_id = $data['id'];
        $data['_rule_list'] = M('PromotionRule')->where(array('promotion_id'=>$promotion_id))->order('sort, id')->select();
        $this->assign('promote_types', C('PROMOTION.PROMOTE_TYPE'));
        $this->assign('limit_types', C('PROMOTION.LIMIT_TYPE'));
    }
    public function update(){
        $id = I('post.id', 0, 'intval');
        $data = I('post.main', array());
        $data['id'] = $id;
        $_M = D(CONTROLLER_NAME);
        $result = $_M->update($data);
        if(false === $result){
            $this->error('更新失败！'.$_M->getError(), '', 3);
        }
        //排序活动规则
        $data_promotion_rules = I('post.promotion_rules', array());
        if(!empty($data_promotion_rules)){
            $_Rule = M('PromotionRule');
            foreach($data_promotion_rules as $key => $id){
                $_Rule->save(array(
                    'id' => $id,
                    'sort' => $key
                ));
            }
        }
        
        redirect(U('index'));
    }
    protected function _trigger_delete($ids){
        M('PromotionRule')->where(array('promotion_id'=>array('in', $ids)))->delete();
        M('PromotionLimitation')->where(array('promotion_id'=>array('in', $ids)))->delete();
    }
}