<?php
namespace Admin\Controller;
class OrderController extends CommonController {

    public function index() {
        $condition = array();
        $order_status = I('get.order_status', 0, 'intval');
        $order_code = I('get.code','');
        $start_time = I('get.start_time','');
        $end_time = I('get.end_time','');
        if($order_status > 0){
            $condition['o.order_status'] = $order_status;
        }
        if(!empty($order_code)){
            $condition['o.order_code'] = $order_code;
        }
        if(!empty($start_time) && !empty($end_time)){
            $condition['o.order_time'] = array('between', array(strtotime($start_time.' 00:00:00'),strtotime($end_time.' 23:59:59')));
        }elseif(!empty($start_time)){
            $condition['o.order_time'] = array('egt',  strtotime($start_time.' 00:00:00'));
        }elseif(!empty($end_time)){
            $condition['o.order_time'] = array('elt',  strtotime($end_time.' 23:59:59'));
        }
        $_M = M(CONTROLLER_NAME);
        $_P = new \Think\Page(0);
        $list = $_M->alias('o')
                        ->field("SQL_CALC_FOUND_ROWS o.*, m.account")
                        ->join("LEFT JOIN __MEMBER__ m ON m.id = o.member_id")
                        ->where($condition)
                        ->limit($_P->firstRow . ',' . $_P->listRows)->order('o.id desc')->select();
        $result = $_M->query("SELECT FOUND_ROWS() as count");
        $_P->totalRows = $result[0]['count'];
        $this->assign('page_nav', $_P->show());
        $this->assign('list', $list);
        $this->assign('opts', array(
            'order_status' => $order_status,
            'code' => $order_code,
            'start_time' => $start_time,
            'end_time' => $end_time
        ));
        $this->assign('order_status_list', M('OrderStatus')->getField('id,order_status_name'));
        cookie('redirectUrl', __SELF__);
        $this->display();
    }

    public function add() {
        $member_id = I('get.mid', 0, 'intval');
        if ($member_id < 1) {
            $level_id = I('get.level_id', -1, 'intval');
            $kw = I('get.kw', '');
            $condition = array("m.level_id = ml.id AND m.id = mi.member_id");
            if ($level_id != -1) {
                $condition[] = "m.level_id = {$level_id}";
            }
            if (!empty($kw)) {
                $condition[] = "(m.account like '%" . addslashes($kw) . "%' OR m.username like '%" . addslashes($kw) . "%')";
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
            cookie('redirectUrl', __SELF__);
            $this->display();
        } else {
            $this->assign('member', M('Member')->field("id,account")->find($member_id));
            $address_list = M('MemberAddress')->where(array('member_id' => $member_id))->order("is_default desc,id")->select();
            $this->assign('address_list', $address_list);
            $_Region = M('Region');
            $this->assign('province_list', $_Region->where(array('pid' => 0))->order('sort desc,id')->select());
            if ($address_list) {
                $address = $address_list[0];
                $this->assign('address', $address);
                if ($address['province_id'] > 0) {
                    $this->assign('city_list', $_Region->where(array('pid' => $address['province_id']))->order('sort desc,id')->select());
                }
                if ($address['city_id'] > 0) {
                    $this->assign('zone_list', $_Region->where(array('pid' => $address['city_id']))->order('sort desc,id')->select());
                }
            }
            $this->assign('region_list', $_Region->getField('id,region_name'));

            $this->display('addorder');
        }
    }

    public function insert() {
        $main = I('post.main', array());
        $items = I('post.items', array());
        $addr = I('post.addr', array());
        if (empty($main) || empty($items) || empty($addr)) {
            $this->error('参数错误！');
        }
        $member_id = intval($main['member_id']);
        if ($member_id < 1) {
            $this->error('缺少会员ID！');
        }
        //调出产品
        $product_ids = array_keys($items);
        $_Product = M('Product');
        $condition = array(
            'p.product_id' => array('in', $product_ids)
        );
        $products = $_Product->alias('p')->field("g.id, g.goods_sno, g.goods, g.price, g.image, p.product_id, p.product_sno, p.sku_info")->join("LEFT JOIN __GOODS__ g ON g.id = p.goods_id")->where($condition)->select();
        if (!$products) {
            $this->error('找不到产品！');
        }
        //计算总额
        $cost_items = 0;
        $cost_shipping = $main['cost_shipping'];
        $discount = $main['discount'];
        foreach ($products as $key => $product) {
            $amount = intval($items[$product['product_id']]);
            $amount = $amount < 1 ? 1 : $amount;
            $products[$key]['amount'] = $amount;
            $cost_items += $product['price'] * $amount;
        }
        $total_amount = $cost_items + $cost_shipping - $discount;
        if ($cost_items <= 0 || $total_amount <= 0) {
            $this->error('产品总价不能为零！');
        }
        $main['cost_items'] = $cost_items;
        $main['total_amount'] = $total_amount;
        if (empty($main['order_time'])) {
            $main['order_time'] = date('Y-m-d H:i:s');
        }
        $order_code = $this->_getOrderCode();
        $main['order_code'] = $order_code;
        //添加订单
        $_M = D(CONTROLLER_NAME);
        $order_id = $_M->insert($main);
        if (!$order_id) {
            $this->error('无法添加订单！');
        }
        //添加订单地址
        $_OrderAddress = M('OrderAddress');
        $addr['order_id'] = $order_id;
        $addr['member_id'] = $member_id;
        $_OrderAddress->add($addr);
        //添加订单产品
        $_OrderItems = M('OrderItems');
        $dataList = array();
        foreach ($products as $product) {
            $dataList[] = array(
                'order_id' => $order_id,
                'order_code' => $order_code,
                'member_id' => $member_id,
                'goods_id' => $product['id'],
                'goods_sno' => $product['goods_sno'],
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'amount' => $product['amount'],
                'extra' => json_encode(array(
                    'name' => $product['goods'],
                    'product_sno' => $product['product_sno'],
                    'sku_info' => $product['sku_info'],
                    'image' => $product['image'],
                )),
            );
        }
        $_OrderItems->addAll($dataList);
        //记录日志
        $this->_writeLog($order_id, '生成新订单');
        
        redirect(U('edit', array('id' => $order_id)));
    }
    
    protected function _trigger_edit(&$data) {
        $order_id = $data['id'];
        $data['_member'] = M('Member')->field("account")->where(array('id' => $data['member_id']))->find();
        $data['_addr'] = M('OrderAddress')->where(array('order_id' => $order_id))->find();
        $items = M('OrderItems')->where(array('order_id' => $order_id))->select();
        foreach ($items as $key => $item) {
            $items[$key]['_data'] = json_decode($item['extra'], true);
        }
        $data['_items'] = $items;
        $this->assign('order_status_list', M('OrderStatus')->getField('id,order_status_name'));
        $this->assign('region_list', M('Region')->getField('id,region_name'));
        $this->assign('express_list', M('Express')->getField('id,express'));
        $this->assign('log_list', M('OrderLog')->alias('l')->field("l.*,u.account as handler")->join("LEFT JOIN __USER__ u ON u.id = l.handler_id")->where(array('l.order_id'=>$order_id))->order('l.id')->select());
        $refund_list = M('Refund')->where(array('order_id'=>$order_id))->order('id')->select();
        if(!empty($refund_list)){
            $_RefundItems = M('RefundItems');
            foreach($refund_list as $key => $vo){
                $items = $_RefundItems->where(array('refund_id'=>$vo['id']))->select();
                if(!empty($items)){
                    foreach ($items as $k => $item){
                        $items[$k]['_extra'] = json_decode($item['extra'], true);
                    }
                }
                $refund_list[$key]['_items'] = $items;
            }
        }
        $this->assign('refund_list', $refund_list);
    }
    public function update(){
        redirect('index');
    }
    /**
     * 复制订单
     */
    public function copy(){
        $id = I('get.id', 0, 'intval');
        if($id < 1){
            redirect('index');
        }
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field('member_id,cost_items,cost_shipping,need_invoice,invoice_title,invoice_type')->where(array('id'=>$id))->find();
        if(!$order){
            redirect('index');
        }
        $_OrderAddress = M('OrderAddress');
        $order_address = $_OrderAddress->where(array('order_id'=>$id))->find();
        $_OrderItems = M('OrderItems');
        $order_items = $_OrderItems->where(array('order_id'=>$id))->select();
        //添加新订单
        $order_code = $this->_getOrderCode();
        $order['order_code'] = $order_code;
        $order['total_amount'] = $order['cost_items'] + $order['cost_shipping'];
        $order['order_status'] = 1;
        $order['order_ip'] = get_client_ip();
        $order['order_time'] = NOW_TIME;
        $order_id = $_M->add($order);
        //添加地址
        $order_address['order_id'] = $order_id;
        $_OrderAddress->add($order_address);
        //添加产品
        $dataList = array();
        foreach ($order_items as $item){
            $item['order_id'] = $order_id;
            $item['order_code'] = $order_code;
            $dataList[] = $item;
        }
        $_OrderItems->addAll($dataList);
        redirect(U('edit',array('id'=>$order_id)));
    }

    /**
     * 修改订单地址
     */
    public function ajaxeditaddress() {
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if (IS_POST) {
            $addr = I('post.addr', array());
            if (empty($addr)) {
                $this->error('参数错误！');
            }
            M('OrderAddress')->save(array(
                'order_id' => $order['id'],
                'province_id' => $addr['province_id'],
                'city_id' => $addr['city_id'],
                'zone_id' => $addr['zone_id'],
                'address' => $addr['address'],
                'zipcode' => $addr['zipcode'],
                'truename' => $addr['truename'],
                'mobile' => $addr['mobile'],
                'phone' => $addr['phone'],
            ));
            $this->_writeLog($order['id'], '修改订单地址');
            $this->success();
        } else {
            $order_id = $order['id'];
            $address = M('OrderAddress')->where(array('order_id' => $order_id))->find();
            $this->assign('address', $address);

            $_Region = M('Region');
            $this->assign('province_list', $_Region->where(array('pid' => 0))->order('sort desc,id')->select());
            if ($address['province_id'] > 0) {
                $this->assign('city_list', $_Region->where(array('pid' => $address['province_id']))->order('sort desc,id')->select());
            }
            if ($address['city_id'] > 0) {
                $this->assign('zone_list', $_Region->where(array('pid' => $address['city_id']))->order('sort desc,id')->select());
            }
            $this->assign('order_code', $order_code);
            $this->display();
        }
    }

    /**
     * 支付订单
     */
    public function payorder() {
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_code,total_amount,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单！');
        }
        if ($order['order_status'] != 1) {
            $this->error('该订单不能支付！');
        }
        if(IS_POST){
            $pay_method = I('post.pay_method', 1, 'intval');
            $pay_time = I('post.pay_time');
            $pay_amount = I('post.pay_amount', 'floatval');
            if ($pay_amount < $order['total_amount']) {
                $this->error('支付金额不足，请重新支付！');
            }
            $_M->save(array(
                'id' => $order['id'],
                'is_paid' => 1,
                'pay_method' => $pay_method,
                'pay_amount' => $pay_amount,
                'pay_time' => strtotime($pay_time),
                'order_status' => 3
            ));
            $this->_writeLog($order['id'], '支付订单');
            $this->success();
        }else{
            $this->assign('order', $order);
            $this->display();
        }
    }

    /**
     * 订单发货
     */
    public function shiporder() {
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单！');
        }
        if ($order['order_status'] != 3) {
            $this->error('该订单不能发货！');
        }
        $_M->where(array('order_code' => $order_code))->setField('order_status', 4);
        $this->_writeLog($order['id'], '订单发货');
        $this->success();
    }

    /**
     * 修改发货信息
     */
    public function ajaxeditexpress() {
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_code,express_id,express_bill,ship_time,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if ($order['order_status'] != 4) {
            $this->error('不能修改发货信息！');
        }
        if (IS_POST) {
            $express_id = I('post.express_id', 0, 'intval');
            $express_bill = I('post.express_bill', '');
            $ship_time = I('post.ship_time');
            $_M->save(array(
                'id' => $order['id'],
                'express_id' => $express_id,
                'express_bill' => $express_bill,
                'ship_time' => empty($ship_time) ? NOW_TIME : strtotime($ship_time)
            ));
            $this->_writeLog($order['id'], '修改发货信息');
            $this->success();
        } else {
            $this->assign('express_list', M('Express')->select());
            $this->assign('data', $order);
            $this->display();
        }
    }
    /**
     * 修改发票信息
     */
    public function ajaxeditinvoice(){
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_code,need_invoice,invoice_type,invoice_title,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if ($order['order_status'] > 3) {
            $this->error('不能修改发票信息！');
        }
        if (IS_POST) {
            $need_invoice = I('post.need_invoice', 0, 'intval');
            $invoice_type = I('post.invoice_type', 1, 'intval');
            $invoice_title = I('post.invoice_title', '');
            $_M->save(array(
                'id' => $order['id'],
                'need_invoice' => $need_invoice,
                'invoice_type' => $invoice_type,
                'invoice_title' => $invoice_title,
            ));
            $this->_writeLog($order['id'], '修改发票信息');
            $this->success();
        } else {
            $this->assign('express_list', M('Express')->select());
            $this->assign('data', $order);
            $this->display();
        }
    }
    public function ajaxeditremark(){
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,remark")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        $remark = I('post.remark', '');
        $_M->save(array(
            'id' => $order['id'],
            'remark' => $remark
        ));
        $this->success();
    }

    /**
     * 编辑订单产品
     */
    public function ajaxeditproducts() {
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_code,member_id,cost_items,cost_shipping,discount_coupon,discount_promotion,discount,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if ($order['order_status'] > 3) {
            $this->error('不能编辑订单产品！');
        }
        if (IS_POST) {
            $items = I('post.items', array());
            if (empty($items)) {
                $this->error('没有产品！');
            }
            //调出产品
            $product_ids = array_keys($items);
            $_Product = M('Product');
            $condition = array(
                'p.product_id' => array('in', $product_ids)
            );
            $products = $_Product->alias('p')->field("g.id, g.goods_sno, g.goods, g.price, g.image, p.product_id, p.product_sno, p.sku_info")->join("LEFT JOIN __GOODS__ g ON g.id = p.goods_id")->where($condition)->select();
            if (!$products) {
                $this->error('找不到产品！');
            }
            $order_id = $order['id'];
            $member_id = $order['member_id'];
            //计算总额
            $cost_items = 0;
            $cost_shipping = $order['cost_shipping'];
            $discount_coupon = $order['discount_coupon'];
            $discount_promotion = $order['discount_promotion'];
            $discount = $order['discount'];
            foreach ($products as $key => $product) {
                $amount = intval($items[$product['product_id']]);
                $amount = $amount < 1 ? 1 : $amount;
                $products[$key]['amount'] = $amount;
                $cost_items += $product['price'] * $amount;
            }
            $total_amount = $cost_items + $cost_shipping - $discount_coupon - $discount_promotion - $discount;
            if ($cost_items <= 0 || $total_amount <= 0) {
                $this->error('产品总价不能为零！');
            }
            //保存最新订单总价
            $_M->save(array(
                'id' => $order_id,
                'cost_items' => $cost_items,
                'total_amount' => $total_amount
            ));
            $_OrderItems = M('OrderItems');
            //删除原订单产品
            $_OrderItems->where(array('order_id'=>$order_id))->delete();
            //添加订单产品
            $dataList = array();
            foreach ($products as $product) {
                $dataList[] = array(
                    'order_id' => $order_id,
                    'order_code' => $order_code,
                    'member_id' => $member_id,
                    'goods_id' => $product['id'],
                    'goods_sno' => $product['goods_sno'],
                    'product_id' => $product['product_id'],
                    'price' => $product['price'],
                    'amount' => $product['amount'],
                    'extra' => json_encode(array(
                        'name' => $product['goods'],
                        'product_sno' => $product['product_sno'],
                        'sku_info' => $product['sku_info'],
                        'image' => $product['image'],
                    )),
                );
            }
            $_OrderItems->addAll($dataList);
            $this->_writeLog($order_id, '修改订单产品');
            $this->display('Public/reload-opener-window');
        } else {
            $items = M('OrderItems')->where(array('order_id' => $order['id']))->select();
            foreach ($items as $key => $item) {
                $items[$key]['_data'] = json_decode($item['extra'], true);
            }
            $order['_items'] = $items;
            $this->assign('data', $order);
            $this->display();
        }
    }
    /**
     * 修改运费
     * 订单未发货前均可以修改运费
     */
    public function ajaxeditcostship() {
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,cost_items,cost_shipping,discount_coupon,discount_promotion,discount,order_status")->where(array('order_code' => $order_code))->find();
        if(!$order){
            $this->error('找不到该订单号！');
        }
        if($order['order_status']>3){
            $this->error('不能修改运费！');
        }
        $cost_shipping = I('post.cost', 0, 'intval');
        $cost_shipping = $cost_shipping < 0 ? 0 : $cost_shipping;
        $total_amount = $order['cost_items'] + $cost_shipping - $order['discount_coupon'] - $order['discount_promotion'] - $order['discount'];
        $result = $_M->save(array(
            'id' => $order['id'],
            'cost_shipping' => $cost_shipping,
            'total_amount' => $total_amount
        ));
        if(false === $result){
            $this->error('无法修改运费！');
        }
        $this->_writeLog($order['id'], '修改运费', "原运费为：{$order['cost_shipping']}；修改后为：{$cost_shipping}");
        $this->ajaxReturn(array(
            'status' => 1,
            'cost' => number_format($cost_shipping, 2),
            'total_amount' => number_format($total_amount, 2)
        ));
    }
    /**
     * 修改折扣
     * 订单未发货前均可以修改折扣
     */
    public function ajaxeditdiscount() {
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,cost_items,cost_shipping,discount_coupon,discount_promotion,discount,order_status")->where(array('order_code' => $order_code))->find();
        if(!$order){
            $this->error('找不到该订单号！');
        }
        if($order['order_status']>3){
            $this->error('不能修改折扣！');
        }
        $discount = I('post.cost', 0, 'intval');
        $discount = $discount < 0 ? 0 : $discount;
        $total_amount = $order['cost_items'] + $order['cost_shipping'] - $order['discount_coupon'] - $order['discount_promotion'] - $discount;
        $result = $_M->save(array(
            'id' => $order['id'],
            'discount' => $discount,
            'total_amount' => $total_amount
        ));
        if(false === $result){
            $this->error('无法修改折扣！');
        }
        $this->_writeLog($order['id'], '修改折扣', "原折扣为：{$order['discount']}；修改后为：{$discount}");
        $this->ajaxReturn(array(
            'status' => 1,
            'cost' => number_format($discount, 2),
            'total_amount' => number_format($total_amount, 2)
        ));
    }
    /**
     * 交易完成
     */
    public function completeorder(){
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if ($order['order_status']!=4) {
            $this->error('该订单不能完成！');
        }
        $_M->where(array('order_code' => $order_code))->setField('order_status', 5);
        $this->_writeLog($order['id'], '完成订单');
        $this->success();
    }
    /**
     * 取消订单
     */
    public function cancelorder() {
        $order_code = I('get.code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if (!in_array($order['order_status'], array(1,3))) {
            $this->error('该订单不能取消！');
        }
        $_M->where(array('order_code' => $order_code))->setField('order_status', 6);
        $this->_writeLog($order['id'], '取消订单');
        $this->success();
    }
    /**
     * 退货退款
     */
    public function refundorder(){
        $order_code = I('code');
        $_M = M(CONTROLLER_NAME);
        $order = $_M->field("id,member_id,order_code,total_amount,total_refund,order_status")->where(array('order_code' => $order_code))->find();
        if (!$order) {
            $this->error('找不到该订单号！');
        }
        if ($order['order_status']!=5) {
            $this->error('请完成订单后退款！');
        }
        if($order['total_amount']<=$order['total_refund']){
            $this->error('订单已经全额退款！');
        }
        $order_items = M('OrderItems')->where(array('order_id'=>$order['id']))->select();
        if(!$order_items){
            $this->error('找不到该订单产品！');
        }
        $_RefundItems = M('RefundItems');
        $refund_items = $_RefundItems->where(array('order_id'=>$order['id']))->group('product_id')->getField('product_id,sum(amount) as amount');
        if(IS_POST){
            $data_items = I('post.items', array());
            $refund = I('post.refund', 0);
            $remark = I('post.remark', '');
            if(empty($data_items)){
                $this->error("没有产品可以退款！");
            }
            if($refund <= 0){
                $this->error('退款金额不能为0！');
            }
            if($refund > ($order['total_amount'] - $order['total_refund'])){
                $this->error('退款金额超过订单金额！');
            }
            foreach ($data_items as $key => $vo){
                if($vo <= 0){
                    unset($data_items[$key]);
                }
            }
            if(empty($data_items)){
                $this->error("没有产品可以退款！");
            }
            $refund_id = M('Refund')->add(array(
                'order_id' => $order['id'],
                'member_id' => $order['member_id'],
                'refund' => $refund,
                'remark' => $remark,
                'create_time' => NOW_TIME
            ));
            if(!$refund_id){
                $this->error('退货失败！请稍候再试！');
            }
            $_M->save(array(
                'id' => $order['id'],
                'total_refund' => $order['total_refund'] + $refund
            ));
            $dataList = array();
            foreach($order_items as $key => $item){
                if(!isset($data_items[$item['product_id']])){
                    continue;
                }
                $extra = json_decode($item['extra'], true);
                $extra['goods_sno'] = $item['goods_sno'];
                $extra['price'] = $item['price'];
                $refund_amount = isset($refund_items[$item['product_id']]) ? $refund_items[$item['product_id']] : 0;
                $item_amount = $item['amount'] - $refund_amount;
                $amount = $item_amount <= $data_items[$item['product_id']] ? $item_amount : $data_items[$item['product_id']];
                $dataList[] = array(
                    'refund_id' => $refund_id,
                    'order_id' => $order['id'],
                    'goods_id' => $item['goods_id'],
                    'product_id' => $item['product_id'],
                    'amount' => $amount,
                    'extra' => json_encode($extra),
                );
            }
            $_RefundItems->addAll($dataList);
            $this->_writeLog($order['id'], '办理退货/退款');
            $this->display("Public/reload-opener-window");
        }else{
            $item_list = array();
            foreach($order_items as $key => $item){
                $extra = json_decode($item['extra'], true);
                $refund_amount = isset($refund_items[$item['product_id']]) ? $refund_items[$item['product_id']] : 0;
                $amount = $item['amount'] - $refund_amount;
                if($amount<=0){
                    continue;
                }
                $item_list[] = array(
                    'product_id' => $item['product_id'],
                    'goods_sno' => $item['goods_sno'],
                    'sku_info' => $extra['sku_info'],
                    'amount' => $amount,
                    'name' => $extra['name'],
                    'image' => $extra['image'],
                    'price' => $item['price']
                );
            }
            $this->assign('item_list', $item_list);
            $this->assign('data', $order);
            $this->display();
        }
    }
    /**
     * 调取产品
     */
    public function ajaxgetproducts() {
        $category_id = I('get.category_id', 0, 'intval');
        $kw = I('get.kw', '');
        $condition = array(
            "p.goods_id = g.id AND g.category_id = c.id AND g.status = 1 AND p.is_onsale = 1"
        );
        if ($category_id > 0) {
            $condition[] = "g.category_id = {$category_id}";
        }
        if (!empty($kw)) {
            $condition[] = "g.goods like '%" . addslashes($kw) . "%'";
        }
        $sql_where = implode(' AND ', $condition);
        $_M = M('Product');
        $_P = new \Think\Page(0);
        $list = $_M->query("
            SELECT SQL_CALC_FOUND_ROWS g.*, p.*, c.category
            FROM __TABLE__ p, __GOODS__ g, __CATEGORY__ c
            WHERE {$sql_where}
            ORDER BY g.id, p.sku_info
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
    /**
     * 生成订单号
     * @return type
     */
    protected function _getOrderCode() {
        return date('ymd') . substr(time(), -5) . substr(microtime(), 2, 3);
    }
    //记录日志
    protected function _writeLog($order_id, $notes='', $remark=''){
        M('OrderLog')->add(array(
            'order_id' => $order_id,
            'handler_id' => $this->auth['id'],
            'notes' => $notes,
            'remark' => $remark,
            'create_time' => NOW_TIME
        ));
    }
}