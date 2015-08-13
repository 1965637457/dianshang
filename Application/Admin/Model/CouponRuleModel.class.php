<?php
namespace Admin\Model;
class CouponRuleModel extends CommonModel {

    protected $_auto = array(
        array('start_time', 'strtotime', self::MODEL_BOTH, 'function'),
        array('end_time', 'strtotime', self::MODEL_BOTH, 'function'),
    );
}