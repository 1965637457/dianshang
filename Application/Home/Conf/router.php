<?php
defined('THINK_PATH') or exit('No Access!');
return array(
    'URL_ROUTER_ON' => false,
    'URL_ROUTE_RULES' => array(
        'sitemap'               => 'info/sitemap',
        'search'               => 'info/search',
        
        'product/category/:cate_name' => 'product/category',
    ),
);
?>
