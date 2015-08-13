<?php
namespace Admin\Model;
class LetterModel extends CommonModel {

    protected $_auto = array(
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );
}