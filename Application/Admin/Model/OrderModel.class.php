<?php
namespace Admin\Model;
class OrderModel extends CommonModel {

    protected $_auto = array(
        array('order_status', 1, self::MODEL_INSERT),
        array('is_paid', 0, self::MODEL_INSERT),
        array('order_ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
        array('order_time', 'strtotime', self::MODEL_INSERT, 'function'),
    );
}