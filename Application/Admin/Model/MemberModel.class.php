<?php
namespace Admin\Model;
class MemberModel extends CommonModel {

    protected $_auto = array(
        array('pwd', 'crypt', self::MODEL_INSERT, 'function'),
    );
    protected $_validate = array(
        array('account', 'email', '帐号必须是电子邮箱！'),
        array('account', '', '帐号已经存在！',1,'unique',),
    );
    
}