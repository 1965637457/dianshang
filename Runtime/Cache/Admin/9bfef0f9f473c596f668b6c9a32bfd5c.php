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



<form action="<?php echo U('handle');?>" method="post" id="js-form-handle">
    <input type="hidden" name="handle_type" class="js-handle-type" >
    
    
    <div class="table-responsive">
        <table class="table table-condensed table-hover tb-list" id="tree-list">
            <thead>
                <tr>
                    <th class="th-20"><input type="checkbox" class="j-check-all" /></th>
                    <th class="th-50"><?php echo L('LIST_SORT');?></th>
                    <th class="th-40">ID</th>
                    <th><?php echo L('NAME');?>【<a href="#none" onclick="$('#tree-list').treetable('expandAll')"><?php echo L('EXPAND');?></a>|<a href="#none" onclick="$('#tree-list').treetable('collapseAll')"><?php echo L('COLLAPSE');?></a>】</th>
                    <th class="th-150">产品类型</th>
                    <th class="th-60"><?php echo L('STATUS');?></th>
                    <th class="th-150"><?php echo L('ACTIONS');?></th>
                </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr data-tt-id="<?php echo ($v["id"]); ?>" <?php if(($v["pid"]) > "0"): ?>data-tt-parent-id="<?php echo ($v["pid"]); ?>"<?php endif; ?>>
                <td><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" /></td>
                <td><input type="text" value="<?php echo ($v["sort"]); ?>" class="form-control-sm tl-xs js-sort" data-id="<?php echo ($v["id"]); ?>" ></td>
                <td><?php echo ($v["id"]); ?></td>
                <td><a href="<?php echo U('edit',array('id'=>$v['id']));?>"><?php echo ((isset($v["category"]) && ($v["category"] !== ""))?($v["category"]):'== 未定义 =='); ?></a></td>
                <td><?php echo ((isset($v["type_name"]) && ($v["type_name"] !== ""))?($v["type_name"]):'==未关联=='); ?></td>
                <td><button type="button" class="status-<?php echo (intval($v["status"])); ?> j-status" data-url="<?php echo U('setStatus',array('id'=>$v['id'],'field'=>'status','val'=>$v['status']));?>"><?php echo ($v['status']?L('ACTIVE'):L('DISABLED')); ?></button></td>
                <td>
                    <a href="<?php echo U('edit',array('id'=>$v['id']));?>" class="btn btn-success btn-xs"><?php echo L('EDIT');?></a>
                    <a href="<?php echo U('delete',array('id'=>$v['id']));?>" class="btn btn-danger btn-xs j-del"><?php echo L('DELETE');?></a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> <i class="caret"></i></button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo U('privilege',array('rid'=>$v['id']));?>">设置权限</a></li>
                            <li class='divider'></li>
                            <li><a href="<?php echo U('User/search',array('rid'=>$v['id']));?>">查看管理员</a></li>
                            <li><a href="<?php echo U('User/add',array('rid'=>$v['id']));?>">添加管理员</a></li>
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
                    <a href="javascript:;" data-type="delete" class="btn btn-danger btn-sm js-handle js-need-confirm"><i class="glyphicon glyphicon-trash"></i> <?php echo L('DELETE');?></a>
                </div>
                <div class="col-md-8 text-right"><ul class="pagination square"><?php echo ($page_nav); ?></ul></div>
            </div>
        </div>
    
</form>
<div class='notifications top-right'></div>




    <script type="text/javascript" src="/hamanaka3/Public/Admin/js/jquery.treetable.min.js"></script>
    <script>
        $(function() {
            $("#tree-list").treetable({expandable: true, column: 3, initialState: "expanded"});
        });
    </script>



    </body>
</html>