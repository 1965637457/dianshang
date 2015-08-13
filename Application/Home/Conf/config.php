<?php
return array(
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  2,
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
    
    /* 新增模板中替换字符串 */
    'TMPL_PARSE_STRING'     => array(
        '__UPLOAD__'    => __ROOT__ . '/Uploads',
        '__PUBLIC__'    => __ROOT__ . '/Public/'.MODULE_NAME,
        '__IMAGES__'    => __ROOT__ . '/Public/'.MODULE_NAME.'/images',
        '__JS__'        => __ROOT__ . '/Public/'.MODULE_NAME.'/js',
    ),
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文件
    /* 载入扩展配置 */
    'LOAD_EXT_CONFIG' => 'router',
);