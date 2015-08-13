<?php
namespace Admin\Model;
class MemberModel extends CommonModel {

    protected $_auto = array(
        array('pwd', 'crypt', self::MODEL_INSERT, 'function'), 
    );
    protected $_validate = array(
 
    );
    
}