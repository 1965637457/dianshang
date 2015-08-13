<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrator Panel</title>
        <link href="/hamanaka3/Public/Admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/hamanaka3/Public/Admin/style.css" rel="stylesheet">
        <link href="/hamanaka3/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <script src="/hamanaka3/Public/Admin/js/jquery.min.js"></script>
        <script src="/hamanaka3/Public/Admin/bootstrap/js/bootstrap.min.js"></script>
        <script src="/hamanaka3/Public/Admin/js/function.js"></script>
        <script src="/hamanaka3/Public/Admin/js/myplugins.js"></script>
        <script src="/hamanaka3/Public/Admin/js/common.js"></script>
        <script src="/hamanaka3/Public/Admin/js/ckeditor/ckeditor.js"></script>
        <script src="/hamanaka3/Public/Admin/js/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="/hamanaka3/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="/hamanaka3/Public/Admin/js/zDialog/zDialog.min.js"></script>
        <!--[if lt IE 9]>
          <script src="/hamanaka3/Public/Admin/bootstrap/js/html5shiv.min.js"></script>
          <script src="/hamanaka3/Public/Admin/bootstrap/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

<nav class="navbar navbar-defined" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand"><img src="/hamanaka3/Public/Admin/images/logo.png" /></a>
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
    
    
    
        <div class="pull-right page-direction-handle">
            <a href="<?php echo U('add');?>" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i> <?php echo L('ADD');?></a>
            <a href="<?php echo U('index');?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-list"></i> <?php echo L('LIST');?></a>
        </div>
    
</div>


    <form action="" method="get" class="list-search">
        <input type="hidden" name="order_status" value="<?php echo ($opts["order_status"]); ?>" >
        <input type="text" name="code" value="<?php echo ($opts["code"]); ?>" placeholder="订单号" class="form-control-sm t-sm" />
        <input type="text" name="start_time" value="<?php echo ($opts["start_time"]); ?>" placeholder="开始创建时间" class="form-control-sm t-xs js-form-datetime" data-date-format="yyyy-mm-dd" data-min-view='2' /> -
        <input type="text" name="end_time" value="<?php echo ($opts["end_time"]); ?>" placeholder="结束创建时间" class="form-control-sm t-xs js-form-datetime" data-date-format="yyyy-mm-dd" data-min-view='2' />
        
        <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i> <?php echo L('SEARCH');?></button>
    </form>


<form action="<?php echo U('handle');?>" method="post" id="js-form-handle">
    <input type="hidden" name="handle_type" class="js-handle-type" >
    
    
    <div class="table-responsive">
        <table class="table table-condensed table-hover tb-list">
            <thead>
                <tr>
                    <th class="th-40">ID</th>
                    <th>订单号</th>
                    <th class="th-100">总金额</th>
                    <th class="th-100">退款</th>
                    <th class="th-100">积分</th>
                    <th class="th-150">会员帐号</th>
                    <th class="th-150"><?php echo L('CREATE_TIME');?></th>
                    <th class="th-100"><?php echo L('STATUS');?></th>
                    <th class="th-100"><?php echo L('ACTIONS');?></th>
                </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($v["id"]); ?></td>
                    <td><a href="<?php echo U('edit',array('id'=>$v['id']));?>"><?php echo ((isset($v["order_code"]) && ($v["order_code"] !== ""))?($v["order_code"]):'== 未定义 =='); ?></a></td>
                    <td><?php echo C('DOLLAR'); echo (number_format($v["total_amount"],2)); ?></td>
                    <td><?php echo C('DOLLAR'); echo (number_format($v["total_refund"],2)); ?></td>
                    <td><?php echo ($v["integral"]); ?></td>
                    <td><a href="<?php echo U('Member/edit',array('id'=>$v['member_id']));?>"><?php echo ($v["account"]); ?></a></td>
                    <td><?php echo (date('Y-m-d H:i',$v["order_time"])); ?></td>
                    <td><?php echo ($order_status_list[$v['order_status']]); ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> <span class="caret"></span></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="<?php echo U('copy',array('id'=>$v['id']));?>">复制订单</a></li>
                            </ul>
                        </div>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>

    
    
    <div class="container-fluid">
        <div class='row'>
            <div class="col-md-4">
                <a href="<?php echo U('index');?>" class="btn <?php if(($opts["order_status"]) == "0"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">全部订单</a>
                <a href="<?php echo U('index',array('order_status'=>1));?>" class="btn <?php if(($opts["order_status"]) == "1"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">新订单</a>
                <a href="<?php echo U('index',array('order_status'=>3));?>" class="btn <?php if(($opts["order_status"]) == "3"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">待发货</a>
                <a href="<?php echo U('index',array('order_status'=>4));?>" class="btn <?php if(($opts["order_status"]) == "4"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">已发货</a>
                <a href="<?php echo U('index',array('order_status'=>5));?>" class="btn <?php if(($opts["order_status"]) == "5"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">交易完成</a>
                <a href="<?php echo U('index',array('order_status'=>6));?>" class="btn <?php if(($opts["order_status"]) == "6"): ?>btn-primary<?php else: ?>btn-default<?php endif; ?> btn-sm">已取消</a>
            </div>
            <div class="col-md-8 text-right"><ul class="pagination square"><?php echo ($page_nav); ?></ul></div>
        </div>
    </div>

</form>
<div class='notifications top-right'></div>




    <script>
        $(".js-form-datetime").datetimepicker({
            todayBtn: true,
            todayHighlight: true,
            autoclose: true
        });
    </script>



    </body>
</html>