<?php
namespace Admin\Controller;
class GoodsController extends CommonController {
    
    public function index(){
        $_M = M(CONTROLLER_NAME);
        $count = $_M->count();
        if($count){
            $_P = new \Think\Page($count);
            $list = $_M->query("
                SELECT g.*, c.category
                FROM __TABLE__ g, __CATEGORY__ c
                WHERE g.category_id = c.id AND g.status in (0,1)
                ORDER BY g.id
                LIMIT {$_P->firstRow} , {$_P->listRows}
            ");
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        $this->assign('categories', $this->_getListToLevel('Category'));
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    public function search(){
        $category_id = I('get.category_id', 0, 'intval');
        $kw = I('get.kw', '');
        $condition = array(
            'g.status' => array('in', '0,1')
        );
        if($category_id > 0){
            $condition['g.category_id'] = $category_id;
        }
        if(!empty($kw)){
            $condition['g.goods_name'] = array('like', "%{$kw}%");
        }
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->alias('g')->field("SQL_CALC_FOUND_ROWS g.*, c.category")->join("LEFT JOIN __CATEGORY__ c ON c.id = g.category_id")->where($condition)->limit($_P->firstRow.','.$_P->listRows)->select();
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $count = $result[0]['count'];
        $_P->totalRows = $count;
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        $this->assign('categories', $this->_getListToLevel('Category'));
        $this->assign('opts', array(
            'category_id' => $category_id,
            'kw' => $kw
        ));
        cookie('redirectUrl', __SELF__);
        $this->display('index');
    }
    public function _before_add(){ 
   		$this->assign('business',M('business')->field('id,business_name')->select());
        $this->assign('categories', $this->_getListToLevel('Category'));
        $this->assign('type_list', M('Type')->getField('id,type_name,remark'));
    }
    
    public function insert(){
        $info = I('post.info', array());
        $categories = I('post.categories', array());
        $description = I('post.description', array());
        $attrs = I('post.attrs', array());
        
        $_M = D(CONTROLLER_NAME);
        $goods_id = $_M->insert($info);
        if(!$goods_id){
            $this->error('新增产品失败！', '', 3);
        }
        $result = D('GoodsImage')->insert(array('goods_id'=>$goods_id));
        $image = $result ? $result['src'] : '';
        //产品详情
        M('GoodsDescription')->add(array(
            'goods_id' => $goods_id,
            'content' => $description['content']
        ));
        //产品分类
        if($info['category_id']!=0){
            array_unshift($categories, $info['category_id']);
        }
        $this->_doViceCategory($goods_id, $categories, 1);
        //产品属性
        if($attrs){
            $dataList = array();
            $attr_val_ids = array();
            foreach ($attrs as $attr){
                $attr_val_ids += $attr;
            }
            foreach($attr_val_ids as $attr_val_id){
                $dataList[] = array(
                    'goods_id' => $goods_id,
                    'attr_val_id' => $attr_val_id
                );
            }
            M('GoodsAttribute')->addAll($dataList);
        }
        //货品
        $_Product = M('Product');
        $default_product = $_Product->add(array(
            'goods_id' => $goods_id,
            'product_sno' => $info['goods_sno'],
            'stock' => $info['stock'],
            'is_onsale' => $info['status'],
        ));
        //保存默认货品ID
        if($default_product){
            $_M->save(array(
                'id' => $goods_id,
                'image' => $image,
                'default_product' => $default_product
            ));
        }
        
        $this->redirect('index');
    }
    protected function _trigger_edit(&$data){
        $goods_id = $data['id'];
        $data['descriptions'] = M('GoodsDescription')->where(array('goods_id'=>$goods_id))->find();
        $data['categories'] = M('GoodsCategory')->where(array('goods_id'=>$goods_id,'is_primary'=>0))->getField("GROUP_CONCAT(category_id)");
        $data['images'] = M('GoodsImage')->where(array('goods_id'=>$goods_id))->order('sort,image_id')->getField('image_id,origin_image,small_image,sort');
        $data['products'] = M('Product')->where(array('goods_id'=>$goods_id))->order('sku_info')->select();
        //与商品对应的商家
        $this->assign('business',M('business')->field('id,business_name')->where('status=1')->order('id DESC')->select());
        //获取相应产品类型的关联属性
        $this->assign('attributes', $this->_getAttributes($data['type_id']));
        //读取产品已关联信息
        $chosen_attribute_ids = M('GoodsAttribute')->where(array('goods_id'=>$goods_id))->getField("GROUP_CONCAT(attr_val_id)");
        $this->assign('chosen_attribute_ids', $chosen_attribute_ids);
        //分类
        $this->assign('categories', $this->_getListToLevel('Category'));
        $this->assign('type_list', M('Type')->getField('id,type_name,remark'));
    }
    public function update(){
        $goods_id = I('post.id', 0, 'intval');
        $info = I('post.info', array());
        $categories = I('post.categories', array());
        $description = I('post.description', array());
        $images = I('post.images', array());
        $products = I('post.products', array());
        $attrs = I('post.attrs', array());
        
        $info['id'] = $goods_id;
        $_M = D(CONTROLLER_NAME);
        $result = $_M->update($info);
        if(false === $result){
            $this->error('更新产品失败！', '', 3);
        }
        //产品详情
        M('GoodsDescription')->save(array(
            'goods_id' => $goods_id,
            'content' => $description['content']
        ));
        //产品分类
        if($info['category_id']!=0){
            array_unshift($categories, $info['category_id']);
        }
        $this->_doViceCategory($goods_id, $categories, 1);
        //产品图片排序
        if($images){
            $_GoodsImage = M('GoodsImage');
            foreach($images as $sort => $image_id){
                $_GoodsImage->save(array(
                    'image_id' => $image_id,
                    'sort' => $sort
                ));
            }
        }
        //货品信息
        $_Product = D('Product');
        if(empty($products)){
            $_Product->save(array(
                'product_id' => $info['default_product'],
                'product_sno' => $info['goods_sno'],
                'stock' => $info['stock'],
                'is_onsale' => $info['status'],
            ));
        }else{
            $total_stock = 0;
            foreach($products as $product_id => $product){
                $total_stock += $product['stock'];
                $product['product_id'] = $product_id;
                $product['sku_images'] = isset($product['sku_images']) ? implode(',', $product['sku_images']) : '';
                $_Product->save($product);
            }
            //保存总库存
            $_M->save(array(
                'id' => $goods_id,
                'stock' => $total_stock
            ));
        }
        //传过来attrs参数，删除系统中非attrs的记录，添加attrs参数
        $_GoodsAttr = M('GoodsAttribute');
        if(empty($attrs)){
            $_GoodsAttr->where(array('goods_id'=>$goods_id))->delete();
        }else{
            $attr_val_ids = array();
            foreach ($attrs as $attr){
                $attr_val_ids = array_merge($attr_val_ids, $attr);
            }
            $_GoodsAttr->where(array('goods_id'=>$goods_id, 'attr_val_id'=>array('not in', $attr_val_ids)))->delete();
            $dataList = array();
            foreach($attr_val_ids as $attr_val_id){
                $dataList[] = array(
                    'goods_id' => $goods_id,
                    'attr_val_id' => $attr_val_id
                );
            }
            $_GoodsAttr->addAll($dataList);
        }
        redirect(cookie('redirectUrl'));
    }
    /**
     * 把产品放入回收站
     */
    public function recycle(){
        $_M = D(CONTROLLER_NAME);
        $id = I('id', 0, 'intval');
        empty($id) && $this->error('参数有误', cookie('redirectUrl'), 2);
        $ids = is_array($id) ? $id : array($id);
        $condition = array(
            'id' => array('in', $ids)
        );
        $result = $_M->where($condition)->setField('status', -1);
        if($result){
            if(IS_AJAX){
                $this->success('删除成功');
            }else{
                redirect(cookie('redirectUrl'));
            }
        }else{
            $this->error('删除失败',  cookie('redirectUrl'), 2);
        }
    }
    /**
     * 产品回收站管理
     */
    public function recyclebin(){
        $_M = M(CONTROLLER_NAME);
        $count = $_M->count();
        if($count){
            $_P = new \Think\Page($count);
            $list = $_M->query("
                SELECT g.*, c.category
                FROM __TABLE__ g, __CATEGORY__ c
                WHERE g.category_id = c.id AND g.status = -1
                ORDER BY g.id
                LIMIT {$_P->firstRow} , {$_P->listRows}
            ");
            $this->assign('page_nav', $_P->show());
            $this->assign('list', $list);
        }
        
        cookie('redirectUrl', __SELF__);
        $this->display();
    }
    protected function _trigger_delete($ids){
        D('GoodsImage')->where(array('goods_id'=>array('in',$ids)))->delete();
        M('GoodsAttribute')->where(array('goods_id'=>array('in',$ids)))->delete();
        M('GoodsCategory')->where(array('goods_id'=>array('in',$ids)))->delete();
        M('GoodsDescription')->where(array('goods_id'=>array('in',$ids)))->delete();
    }
    /**
     * 获取产品类型关联的属性
     */
    public function getattributes(){
        $goods_id = I('get.goodsid', 0, 'intval');
        $type_id = I('get.typeid', 0, 'intval');
        
        $list = $this->_getAttributes($type_id);
        if(!$list){
            $this->error("暂无相关属性");
        }
        //读取产品已关联信息
        $chosen_attribute_ids = M('GoodsAttribute')->where(array('goods_id'=>$goods_id))->getField("GROUP_CONCAT(attr_val_id)");
        
        $this->assign('attributes', $list);
        $this->assign('chosen_attribute_ids', $chosen_attribute_ids);
        $this->ajaxReturn(array(
            'status' => 1,
            'info' => $this->fetch()
        ));
    }
    protected function _getAttributes($type_id){
        if($type_id < 1){
            return false;
        }
        $attributes = M('TypeAttribute')->alias('ta')->field("a.*")->join("LEFT JOIN __ATTRIBUTE__ a ON a.id = ta.attribute_id")->where(array("ta.type_id"=>$type_id))->order("ta.sort")->select();
        if(!$attributes){
            return false;
        }
        $list = array();
        $attribute_ids = array();
        foreach($attributes as $attribute){
            $attribute_ids[] = $attribute['id'];
            $list[$attribute['id']] = $attribute;
        }
        $attribute_values = M('AttributeValue')->where(array("attribute_id"=>array('in', $attribute_ids)))->order('attribute_id,sort')->select();
        if(!$attribute_values){
            return false;
        }
        foreach($attribute_values as $attribute_value){
            $attribute_id = $attribute_value['attribute_id'];
            $list[$attribute_id]['_list'][] = $attribute_value;
        }
        return $list;
    }
    /**
     * 重新加载货品信息
     */
    public function loadproduct(){
        $id = I('get.id', 0, 'intval');
        $data = M('Goods')->find($id);
        $data['images'] = M('GoodsImage')->where(array('goods_id'=>$data['id']))->order('sort,image_id')->getField('image_id,origin_image,small_image,sort');
        $data['products'] = M('Product')->where(array('goods_id'=>$id))->order('sku_info')->select();
        $this->assign('data', $data);
        $this->display();
    }
    public function pickproductimage(){
        $goods_id = I('get.id', 0, 'intval');
        $_Image = M('GoodsImage');
        $image_list = $_Image->where(array('goods_id'=>$goods_id))->order('sort')->select();
        $this->assign('image_list', $image_list);
        
        $this->display();
    }
    public function ajaxpickprimaryimage(){
        $goods_id = I('get.id', 0, 'intval');
        $_Image = M('GoodsImage');
        $image_list = $_Image->where(array('goods_id'=>$goods_id))->order('sort')->select();
        $this->assign('image_list', $image_list);
        
        $this->display();
    }
    /**
     * 处理产品多分类的情况
     * @param intval $goods_id
     * @param array $categories 分类数组
     * @param intval $type 1表示新增 2表示更新
     */
    protected function _doViceCategory($goods_id=0, $categories=array(), $type = 1){
        if($goods_id<=0){
            return;
        }
        $_GoodsCategory = M('GoodsCategory');
        //分类数组为空，删除产品所有分类
        if(empty($categories)){
            $_GoodsCategory->where(array('goods_id'=>$goods_id))->delete();
            return;
        }
        if($type===2){
            $_GoodsCategory->where(array('goods_id' => $goods_id))->delete();
        }
        //处理分类
        $primary_category_id = $categories[0];
        $categories = array_unique($categories);
        $dataList = array();
        foreach ($categories as $category){
            $dataList[] = array(
                'goods_id' => $goods_id,
                'category_id' => $category,
                'is_primary' => $category === $primary_category_id ? 1 : 0
            );
        }
        $_GoodsCategory->addAll($dataList);
    }
    public function uploadimage(){
        $_GoodsImage = D('GoodsImage');
        $result = $_GoodsImage->insert();
        if(false === $result){
            $this->error('上传出错！'.$_GoodsImage->getError());
        }else{
            $this->ajaxReturn(array(
                'status' => 1,
                'src' => $result['src'],
                'id' => $result['id']
            ));
        }
    }
    public function removeimage(){
        $image_id = I('get.image_id', 0, 'intval');
        $goods_id = I('get.goods_id', 0, 'intval');
        if($image_id<1 || $goods_id<1){
            $this->error('参数错误！');
        }
        $_GoodsImage = D('GoodsImage');
        $result = $_GoodsImage->where(array('goods_id'=>$goods_id,'image_id'=>$image_id))->delete();
        if($result){
            $this->success();
        }else{
            $this->error('无法删除！');
        }
    }
    /**
     * 开启规格，编辑规格
     */
    public function openspecification(){
        $goods_id = I('get.id', 0, 'intval');
        if($goods_id<1){
            $this->error('参数有误，请刷新页面后再开启规格！');
        }
        $_Spec = M('Spec');
        $spec_list = $_Spec->getField("id, spec, spec_alias, display, remark");
        if(!$spec_list){
            $this->error('暂时没有规格信息！');
        }
        $_SpecValue = M('SpecValue');
        $spec_values = $_SpecValue->order('spec_id, sort, id')->select();
        if(!$spec_values){
            $this->error('请完善规格信息！');
        }
        foreach ($spec_values as $spec_value){
            $spec_id = $spec_value['spec_id'];
            $spec_list[$spec_id]['_list'][$spec_value['id']] = $spec_value;
        }
        //
        $_Goods = M('Goods');
        $goods = $_Goods->field('id, specification')->find($goods_id);
        if(!empty($goods['specification'])){
            $list = array();
            $specification = json_decode($goods['specification'],true);
            foreach ($specification as $choosen_spec){
                if(isset($spec_list[$choosen_spec['id']])){
                    $spec = $spec_list[$choosen_spec['id']];
                    $spec['_checked'] = 1;
                    unset($spec_list[$choosen_spec['id']]);
                    $spec_value_list = $spec['_list'];
                    $choosen_spec_value_list = $choosen_spec['_list'];
                    $tmp = array();
                    foreach($choosen_spec_value_list as $choosen_spec_value){
                        if(isset($spec_value_list[$choosen_spec_value['id']])){
                            $val = $spec_value_list[$choosen_spec_value['id']];
                            $val['_checked'] = 1;
                            $val['pvt_val'] = $choosen_spec_value['pvt_val'];
                            $val['pvt_img'] = $choosen_spec_value['pvt_img'];
                            $tmp[] = $val;
                            unset($spec_value_list[$choosen_spec_value['id']]);
                        }
                    }
                    if(!empty($spec_value_list)){
                        $tmp += $spec_value_list;
                    }
                    $spec['_list'] = $tmp;
                    $list[] = $spec;
                }
            }
            if(!empty($spec_list)){
                $list += $spec_list;
            }
        }else{
            $list = $spec_list;
        }
        
        $this->assign('spec_list', $list);
        $this->assign('goods_id', $goods_id);
        
        $this->display();
    }
    public function savespecification(){
        //提取参数
        $goods_id = I('post.goods_id', 0, 'intval');
        $specs = I('post.specs', array());
        $spec_value_ids = I('post.spec_value_ids', array());
        $spec_values = I('post.spec_values', array());
        //检查参数有效性
        if($goods_id < 1){
            $this->error('参数有误，请刷新页面后再开启规格！');
        }
        if(empty($specs)){
            $this->error('请选择至少1个、至多2个规格！');
        }
        if(empty($spec_value_ids)){
            $this->error('请选择规格值！');
        }
        //检查是否所有规格都没有选择规格值
        foreach ($specs as $key => $spec_id){
            if(empty($spec_value_ids[$spec_id])){
                unset($specs[$key]);
                unset($spec_values[$spec_id]);
            }
        }
        if(empty($specs)){
            $this->error('请选择规格值！');
        }
        //处理有效规格
        $_Spec = M('Spec');
        $spec_list = $_Spec->where(array('id'=>array('in', $specs)))->getField("id,spec_alias,display");
        if(!$spec_list){
            $this->error('请选择至少1个、至多2个规格！');
        }
        //限制有效规格个数
        $specification = array();
        $limit = 0;
        foreach ($specs as $spec_id){
            //数据库中找不到
            if(!isset($spec_list[$spec_id])){
                continue;
            }
            $choosen_spec = array(
                'id' => $spec_id,
                'spec' => $spec_list[$spec_id]['spec_alias'],
                'display' => $spec_list[$spec_id]['display'],
                '_list' => array()
            );
            foreach($spec_values[$spec_id] as $spec_value_id => $spec_value){
                if(in_array($spec_value_id, $spec_value_ids[$spec_id])){
                    $choosen_spec['_list'][$spec_value_id] = $spec_value;
                }
            }
            $specification[] = $choosen_spec;
            $limit++;
            if($limit===2){
                break;
            }
        }
        $_Goods = M('Goods');
        $goods = $_Goods->field("id,default_product,specification")->find($goods_id);
        //保存已选的规格信息
        $_Goods->save(array(
            'id' => $goods_id,
            'specification' => json_encode($specification)
        ));
        //排列组合生成sku
        $_Product = M('Product');
        $product_list = $_Product->where(array('goods_id'=>$goods_id))->getField('sku_sno,product_id');
        if ($limit === 1){
            foreach($specification[0]['_list'] as $spec_value){
                if(isset($product_list[$spec_value['id']])){
                    $product_id = $product_list[$spec_value['id']];
                    $_Product->save(array(
                        'product_id' => $product_id,
                        'sku_data' => json_encode(array("color"=>$spec_value['pvt_val'],"size"=>"")),
                        'sku_info' => $spec_value['pvt_val'],
                    ));
                    unset($product_list[$spec_value['id']]);
                }else{
                    $_Product->add(array(
                        'goods_id' => $goods_id,
                        'sku_sno' => $spec_value['id'],
                        'sku_data' => json_encode(array("color"=>$spec_value['pvt_val'],"size"=>"")),
                        'sku_info' => $spec_value['pvt_val'],
                        'is_onsale' => 1
                    ));
                }
            }
        }else{
            $dataset = array();
            foreach ($specification as $one){
                $dataset[] = $one['_list'];
            }
            $result = fn_permutate_combine($dataset);
            foreach($result as $vo){
                $sku_sno = $vo[0]['id'] * $vo[1]['id'];
                if(isset($product_list[$sku_sno])){
                    $product_id = $product_list[$sku_sno];
                    $_Product->save(array(
                        'product_id' => $product_id,
                        'sku_data' => json_encode(array("color"=>$vo[0]['pvt_val'],"size"=>$vo[1]['pvt_val'])),
                        'sku_info' => $vo[0]['pvt_val'].','.$vo[1]['pvt_val'],
                    ));
                    unset($product_list[$sku_sno]);
                }else{
                    $product_id = $_Product->add(array(
                        'goods_id' => $goods_id,
                        'sku_sno' => $sku_sno,
                        'sku_data' => json_encode(array("color"=>$vo[0]['pvt_val'],"size"=>$vo[1]['pvt_val'])),
                        'sku_info' => $vo[0]['pvt_val'].','.$vo[1]['pvt_val'],
                        'is_onsale' => 1
                    ));
                }
            }
        }
        if(!empty($product_list)){
            if(in_array($goods['default_product'], $product_list)){
                $_Goods->save(array(
                    'id' => $goods_id,
                    'default_product' => $product_id
                ));
            }
            $_Product->where(array('product_id'=>array('in', $product_list)))->delete();
        }
        $this->assign('goods_id', $goods_id);
        $this->display();
    }
    //关闭规格
    public function closespecification(){
        $goods_id = I('get.id', 0, 'intval');
        $_Goods = M('Goods');
        $data = $_Goods->find($goods_id);
        if(!$data){
            $this->error('找不到该产品！');
        }
        $_Goods->save(array(
            'id' => $goods_id,
            'specification' => ''
        ));
        $_Product = M('Product');
        $_Product->where(array('goods_id'=>$goods_id))->delete();
        $_Product->add(array(
            'product_id' => $data['default_product'],
            'goods_id' => $goods_id,
            'sku_sno' => 0,
            'sku_data' => '',
            'sku_info' => '',
            'product_sno' => $data['goods_sno'],
            'stock' => intval($data['stock']),
            'is_onsale' => $data['status'] == 1 ? 1 : 0
        ));
        $this->success();
    }
    public function setproductstatus(){
        $id = I('get.id',0,'intval');
        $field = I('get.field','');
        $val = I('get.val',0,'intval');
        if($id <= 0 || $field===''){
            $this->error('参数有误', cookie('redirectUrl'));
        }
        $val = $val > 0 ? 0 : 1;
        $_M = M('Product');
        $result = $_M->save(array(
            'product_id' => $id,
            $field => $val
        ));
        if($result){
            if(IS_AJAX){
                $this->ajaxReturn(array(
                    'status' => 1,
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
}