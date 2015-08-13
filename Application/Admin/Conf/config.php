<?php

return array(
    'COOKIE_PREFIX' => 'cart_admin_', // Cookie前缀 避免冲突
    'URL_CASE_INSENSITIVE' => false, // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL' => 1,
    'URL_HTML_SUFFIX' => '', // URL伪静态后缀设置

    /* 后台登录权限 */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 1, // 默认认证类型 1 登录认证 2 实时认证
    'RBAC_ROLE_TABLE' => 'smi_role',
    'RBAC_USER_TABLE' => 'smi_user',
    'RBAC_ACCESS_TABLE' => 'smi_access',
    'RBAC_NODE_TABLE' => 'smi_node',
    'USER_AUTH_KEY' => 'user_auth_id', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'administrator',
    'USER_AUTH_MODEL' => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式
    'USER_AUTH_GATEWAY' => __ROOT__ . '/admin.php/Public/login', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => 'handle', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID

    /* 新增模板中替换字符串 */
    'TMPL_PARSE_STRING' => array(
        '__UPLOAD__' => __ROOT__ . '/Uploads',
        '__PUBLIC__' => __ROOT__ . '/Public/' . MODULE_NAME,
        '__IMAGES__' => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__BOOTSTRAP__' => __ROOT__ . '/Public/' . MODULE_NAME . '/bootstrap',
    ),
    /* 多语言版本 */
    'LANG_SWITCH_ON' => true, //开启语言包功能
    'LANG_AUTO_DETECT' => true, //自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'zh-cn,en-us', //允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE' => 'l', //默认语言切换变量
    
    /* 备份配置 */
    'DATA_BACKUP_PATH' => './Data/',    //数据库备份根路径
    'DATA_BACKUP_PART_SIZE' => '20971520',  //数据库备份卷大小，该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DATA_BACKUP_COMPRESS' => 1,    //是否启用压缩, 压缩备份文件需要PHP环境支持gzopen,gzwrite函数
    'DATA_BACKUP_COMPRESS_LEVEL' => '9', //压缩级别, 数据库备份文件的压缩级别，该配置在开启压缩时生效
                                        //1:普通 4:一般 9:最高
    
    /* 分页 */
    'VAR_PAGE' => 'p',
    'LIST_ROWS' => 20,
    
    'TMPL_ACTION_ERROR'     =>  'Public:dispatch_error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:dispatch_success', // 默认成功跳转对应的模板文件
    
    /* 网站数据配置 */
    'DOLLAR' => '￥',
    'PROMOTION' => array(
        'PROMOTE_TYPE' => array(
            1 => '现金减免 —— 如：满200减100',
            2 => '折扣优惠 —— 如：满200打8折',
            3 => '现金逢减 —— 如：逢200减10'
        ),
        'LIMIT_TYPE' => array(
            1 => '全部商品',
            2 => '指定商品',
            3 => '指定分类'
        ),
    ),
    'COUPON_CONFIG' => array(
        'COUPON_TYPE' => array(
            1 => '现金券',
            2 => '折扣券',
        ),
        'TARGET_TYPE' => array(
            1 => '向全体会员发放单次有效专属优惠券',
            2 => '向选定会员发放单次有效专属优惠券',
            3 => '按会员等级发放单次有效专属优惠券',
            4 => '发放通用券',
        ),
        'LIMIT_TYPE' => array(
            1 => '全部商品',
            2 => '指定商品',
            3 => '指定分类'
        ),
    ),
);