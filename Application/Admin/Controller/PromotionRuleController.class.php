<?php
namespace Admin\Controller;
class PromotionRuleController extends CommonController {
    
    public function add($promotion_id){
        $this->assign('promote_types', C('PROMOTION.PROMOTE_TYPE'));
        $this->assign('limit_types', C('PROMOTION.LIMIT_TYPE'));
        $this->assign('category_list', $this->_getListToLevel('Category'));
        $this->assign('promotion_id', $promotion_id);
        $this->display();
    }
    public function insert(){
        $data_main = I('post.main', array());
        $data_limit_products = I('post.limit_products', array());
        $data_limit_category = I('post.limit_category', array());
        $limit_type = $data_main['limit_type'];
        if(($limit_type==2 && empty($data_limit_products)) || ($limit_type==3 && empty($data_limit_category))){
            $this->error('内容不完整！');
        }
        $_M = D(CONTROLLER_NAME);
        $id = $_M->insert($data_main);
        if(!$id){
            $this->error('添加失败！');
        }
        $promotion_id = $data_main['promotion_id'];
        $_Limitation = M('PromotionLimitation');
        if($limit_type == 2) {
            $list = $data_limit_products;
        }elseif($limit_type == 3){
            $list = $data_limit_category;
        }
        if($list){
            $dataList = array();
            foreach ($list as $object_id){
                $dataList[] = array(
                    'promotion_id' => $promotion_id,
                    'promotion_rule_id' => $id,
                    'limit_type' => $limit_type,
                    'object_id' => $object_id
                );
            }
            $_Limitation->addAll($dataList);
        }
        //回调数据
        $promote_types = C('PROMOTION.PROMOTE_TYPE');
        $limit_types = C('PROMOTION.LIMIT_TYPE');
        $data = array(
            'id' => $id,
            'name' => $data_main['promotion_rule'],
            'alias' => $data_main['promotion_rule_alias'],
            'min_amount' => $data_main['min_amount'],
            'promote_type' => $promote_types[$data_main['promote_type']],
            'limit_type' => $limit_types[$data_main['limit_type']],
            'discount' => $data_main['promote_type'] == 2 ? $data_main['discount_rate'].'%' : $data_main['discount_amount'],
        );
        $this->assign('data', json_encode($data));
        $this->display();
    }
    protected function _trigger_edit(&$data){
        $limit_type = $data['limit_type'];
        if($limit_type == 2){
            $data['_product_list'] = M('PromotionLimitation')->alias('pl')->field("g.id, g.goods, g.goods_sno")->join("LEFT JOIN __GOODS__ g ON g.id = pl.object_id")->where(array('promotion_rule_id'=>$data['id']))->select();
        }
        if($limit_type == 3){
            $data['_chosen_category'] = M('PromotionLimitation')->where(array('promotion_rule_id'=>$data['id']))->getField("GROUP_CONCAT(object_id)");
        }
        $this->assign('category_list', $this->_getListToLevel('Category'));
        $this->assign('promote_types', C('PROMOTION.PROMOTE_TYPE'));
        $this->assign('limit_types', C('PROMOTION.LIMIT_TYPE'));
    }
    public function update(){
        $id = I('post.id', 0, 'intval');
        if($id < 1){
            $this->error('参数错误！');
        }
        $data_main = I('post.main', array());
        $data_limit_products = I('post.limit_products', array());
        $data_limit_category = I('post.limit_category', array());
        $limit_type = $data_main['limit_type'];
        if(($limit_type==2 && empty($data_limit_products)) || ($limit_type==3 && empty($data_limit_category))){
            $this->error('内容不完整！');
        }
        $data_main['id'] = $id;
        $_M = D(CONTROLLER_NAME);
        $result = $_M->update($data_main);
        if(false === $result){
            $this->error('更新失败！');
        }
        $promotion_id = $data_main['promotion_id'];
        $_Limitation = M('PromotionLimitation');
        $_Limitation->where(array('promotion_rule_id' => $id))->delete();
        if($limit_type == 2) {
            $list = $data_limit_products;
        }elseif($limit_type == 3){
            $list = $data_limit_category;
        }
        if($list){
            $dataList = array();
            foreach ($list as $object_id){
                $dataList[] = array(
                    'promotion_id' => $promotion_id,
                    'promotion_rule_id' => $id,
                    'limit_type' => $limit_type,
                    'object_id' => $object_id
                );
            }
            $_Limitation->addAll($dataList);
        }
        //回调数据
        $promote_types = C('PROMOTION.PROMOTE_TYPE');
        $limit_types = C('PROMOTION.LIMIT_TYPE');
        $data = array(
            'id' => $id,
            'name' => $data_main['promotion_rule'],
            'alias' => $data_main['promotion_rule_alias'],
            'min_amount' => $data_main['min_amount'],
            'promote_type' => $promote_types[$data_main['promote_type']],
            'limit_type' => $limit_types[$data_main['limit_type']],
            'discount' => $data_main['promote_type'] == 2 ? $data_main['discount_rate'].'%' : $data_main['discount_amount'],
        );
        $this->assign('data', json_encode($data));
        $this->display();
    }
    protected function _trigger_delete($ids){
        M('PromotionLimitation')->where(array('promotion_rule_id' => array('in', $ids)))->delete();
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