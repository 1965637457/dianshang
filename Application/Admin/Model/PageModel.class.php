<?php
namespace Admin\Model;
class PageModel extends CommonModel {
    
    protected $_auto = array(
        array('seo_name', '_handleSeoName', self::MODEL_BOTH, 'callback'),
        array('publish_time', 'strtotime', self::MODEL_BOTH, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );
    
}