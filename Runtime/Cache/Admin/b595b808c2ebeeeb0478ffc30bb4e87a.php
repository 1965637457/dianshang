<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrator Panel</title>
        <link href="/ham/Public/Admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/ham/Public/Admin/style.css" rel="stylesheet">
        <link href="/ham/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <script src="/ham/Public/Admin/js/jquery.min.js"></script>
        <script src="/ham/Public/Admin/bootstrap/js/bootstrap.min.js"></script>
        <script src="/ham/Public/Admin/js/function.js"></script>
        <script src="/ham/Public/Admin/js/myplugins.js"></script>
        <script src="/ham/Public/Admin/js/common.js"></script>
        <script src="/ham/Public/Admin/js/ckeditor/ckeditor.js"></script>
        <script src="/ham/Public/Admin/js/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="/ham/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="/ham/Public/Admin/js/zDialog/zDialog.min.js"></script>
        <!--[if lt IE 9]>
          <script src="/ham/Public/Admin/bootstrap/js/html5shiv.min.js"></script>
          <script src="/ham/Public/Admin/bootstrap/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

<nav class="navbar navbar-defined" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand"><img src="/ham/Public/Admin/images/logo.png" /></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
            <ul class="nav navbar-nav main-nav">
                <li><a href="<?php echo U('Index/index');?>"><span class="glyphicon glyphicon-home"></span></a></li>
                <?php if(is_array($privileges)): $i = 0; $__LIST__ = $privileges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="js-dropdown-hover">
                        <a href="javascript:;"><b><?php echo ($v["$node_title"]); ?></b> <span class="caret"></span></a>
                        <ul class="dropdown-menu  clearfix">
                            <?php if(is_array($v["_child"])): $i = 0; $__LIST__ = $v["_child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li class="dropdown-header"><?php echo ($vv["$node_title"]); ?></li>
                                <?php if(is_array($vv["_child"])): $i = 0; $__LIST__ = $vv["_child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ctrl): $mod = ($i % 2 );++$i; if(is_array($ctrl["_child"])): $i = 0; $__LIST__ = $ctrl["_child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$act): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U($ctrl['name'].'/'.$act['name']);?>"><?php echo ($act["$node_title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                                <li class="divider clearfix"></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="js-dropdown-hover">
                    <a href="javascript:;"><?php echo L('LANG_VERSION');?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="?l=en-us">English</a></li>
                        <li><a href="?l=zh-cn">中文</a></li>
                    </ul>
                </li>
                <li class="js-dropdown-hover">
                    <a href="javascript:;"><span class="glyphicon glyphicon-user"></span> <?php echo ($auth["account"]); ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo U('Profile/editPwd');?>"><span class="glyphicon glyphicon-cog"></span> <?php echo L('MODIFY_PWD');?></a></li>
                        <li><a href="/" target="_blank"><span class="glyphicon glyphicon-new-window"></span> <?php echo L('VIEW_FRONT');?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo U('Public/logout');?>"><span class="glyphicon glyphicon-log-out"></span> <?php echo L('LOGOUT');?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="page-direction clearfix">
    
    
        <div class="pull-left bread_nav">
            <i class="glyphicon glyphicon-tower"></i> <?php echo ($bread_nav_ctrl); ?>
        </div>
    
    
    
</div>


    <div class="container-fluid container-dashboard">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">新闻中心</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo L('TITLE');?></th>
                                <th><?php echo L('PUBLISH_TIME');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>这是一条新闻</td>
                                <td class="th-150">2014-10-10</td>
                            </tr>
                            <tr>
                                <td>这是一条新闻</td>
                                <td class="th-150">2014-10-10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">系统信息</div>
                    <table class='table'>
                        <tr>
                            <th>网站域名：</th>
                            <td><?php echo ($_SERVER['SERVER_NAME']); ?></td>
                        </tr>
                        <tr>
                            <th>服务器IP：</th>
                            <td><?php echo (gethostbyname($_SERVER['SERVER_NAME'])); ?></td>
                        </tr>
                        <tr>
                            <th>操作系统：</th>
                            <td><?php echo PHP_OS;?></td>
                        </tr>
                        <tr>
                            <th>服务器：</th>
                            <td><?php echo ($_SERVER['SERVER_SOFTWARE']); ?></td>
                        </tr>
                        <tr>
                            <th>接口类型：</th>
                            <td><?php echo php_sapi_name();?></td>
                        </tr>
                        <tr>
                            <th>PHP版本：</th>
                            <td><?php echo phpversion();?></td>
                        </tr>
                        <tr>
                            <th>MYSQL：</th>
                            <td><?php echo mysql_get_server_info();?></td>
                        </tr>
                        <tr>
                            <th>内核版本：</th>
                            <td><?php echo (THINK_VERSION); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

<div class='notifications top-right'></div>






    </body>
</html>