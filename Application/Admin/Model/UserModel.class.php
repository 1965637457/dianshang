<?php
namespace Admin\Model;
class UserModel extends CommonModel {
    
    protected $_validate = array(
        array('account', '1,16', '用户名长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
        array('account', '', '用户名被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
        array('password', '/^\w{6,12}$/', '密码长度为6-12个由字母、数字、下划线组成的字符', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),//新增时必须验证
        array('password', '/^\w{6,12}$/', '密码长度为6-12个由字母、数字、下划线组成的字符', self::EXISTS_VALIDATE, 'regex', self::MODEL_UPDATE),//更新时存在就验证
    );
    protected $_auto = array(
        array('password', 'crypt', self::MODEL_INSERT, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );
}