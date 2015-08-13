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
<form action="<?php echo U('update');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
    
    
    <ul class="nav nav-tabs">
        <li class="active"><a data-target="#pane1" data-toggle="tab">基本信息</a></li>
        <li><a data-target="#pane2" data-toggle="tab">地址信息</a></li>
        <li><a data-target="#pane3" data-toggle="tab">购买列表</a></li>
        <li><a data-target="#pane4" data-toggle="tab">收藏列表</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="pane1">
            <table class="table table-condensed tb-data">
                <tr>
                    <th><?php echo L('STATUS');?></th>
                    <td>
                        <label><input type="radio" name="main[status]" value="1" checked /> <?php echo L('ACTIVE');?></label>
                        <label><input type="radio" name="main[status]" value="0" <?php if(($data["status"]) == "0"): ?>checked<?php endif; ?> /> <?php echo L('DISABLED');?></label>
                    </td>
                </tr>
                <tr>
                    <th><?php echo L('LEVEL');?></th>
                    <td>
                        <select name="main[level_id]" class="form-control-sm">
                            <option value="0">请选择...</option>
                            <?php if(is_array($level_list)): $i = 0; $__LIST__ = $level_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($data["level_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["member_level"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo L('ACCOUNT');?></th>
                    <td><input type="text" value="<?php echo (htmlspecialchars($data["account"])); ?>" disabled class="form-control-sm" /> <i>用于会员登录</i></td>
                </tr>
                <tr>
                    <th><?php echo L('CREATE_TIME');?></th>
                    <td><input type="text" value="<?php echo (date('Y-m-d H:i',$data["_info"]["register_time"])); ?>" disabled class="form-control-sm" /></td>
                </tr>
                <tr>
                    <th><?php echo L('USERNAME');?></th>
                    <td><input type="text" name="main[username]" value="<?php echo (htmlspecialchars($data["username"])); ?>" class="form-control-sm" /></td>
                </tr>
                <tr>
                    <th><?php echo L('REMARK');?></th>
                    <td><textarea name="info[remark]" class="form-control-sm"><?php echo (htmlspecialchars($data["_info"]["remark"])); ?></textarea></td>
                </tr>
            </table>
        </div>
        <div class="tab-pane" id="pane2">
            <table class="table table-condensed table-bordered tb-data-list" id="js-address-list" data-reload-url="<?php echo U('MemberAddress/reloadaddress',array('mid'=>$data['id']));?>" data-add-url="<?php echo U('MemberAddress/add',array('mid'=>$data['id']));?>" data-edit-url="<?php echo U('MemberAddress/edit');?>" data-remove-url="<?php echo U('MemberAddress/delete');?>">
                <thead>
                    <tr>
                        <th class="th-120">姓名</th>
                        <th class="th-120">电话</th>
                        <th class="th-120">手机</th>
                        <th>地址</th>
                        <th class="th-120">操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(is_array($data["_address_list"])): $i = 0; $__LIST__ = $data["_address_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($v["id"]); ?>">
                        <td><?php echo ($v["truename"]); ?></td>
                        <td><?php echo ($v["phone"]); ?></td>
                        <td><?php echo ($v["mobile"]); ?></td>
                        <td><?php echo ($region_list[$v['province_id']]); echo ($region_list[$v['city_id']]); echo ($region_list[$v['zone_id']]); ?>, <?php echo ($v["address"]); ?>, <?php echo ($v["zipcode"]); ?></td>
                        <td class="f16">
                            <a href="javascript:;" class="js-edit-item"><span class="glyphicon glyphicon-edit"></span></a>
                            <a href="javascript:;" class="js-remove-item"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><button type="button" class="btn btn-success btn-xs" id="js-add-address">添加</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="tab-pane" id="pane3">
            <table class="table table-condensed table-bordered tb-data-list" id="js-address-list" data-reload-url="<?php echo U('MemberAddress/reloadaddress',array('mid'=>$data['id']));?>" data-add-url="<?php echo U('MemberAddress/add',array('mid'=>$data['id']));?>" data-edit-url="<?php echo U('MemberAddress/edit');?>" data-remove-url="<?php echo U('MemberAddress/delete');?>">
                <thead>
                    <tr>
                        <th class="th-120">姓名</th>
                        <th class="th-120">商品名</th>
                        <th class="th-120">商品分类</th>
                        <th class="th-120">OnSale</th>
                        <th class="th-120">SkuIofo</th>
                        <th class="th-120">原价</th>
                        <th class="th-120">售价</th>
                        <th class="th-120">stack</th>
                        <th class="th-120">手机</th> 
                        <th class="th-120">是否付款</th> 
                        <th>地址</th>
                        <th class="th-120">操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(is_array($data["_bug_list"])): $i = 0; $__LIST__ = $data["_bug_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$b): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($b["id"]); ?>">
                        <td><?php echo ($b["truename"]); ?></td>
                        <td><?php echo ($b["goods"]); ?></td>
                        <td><?php echo ($b["category"]); ?></td>
                        <td><?php echo ($b["is_onsale"]); ?></td>
                        <td><?php echo ($b["sku_info"]); ?></td>
                        <td><?php echo ($b["original_price"]); ?></td> 
                        <td><?php echo ($b["price"]); ?></td>
                        <td><?php echo ($b["stock"]); ?></td>
                        <td><?php echo ($b["mobile"]); ?></td>
                        <td><?php echo ($b["is_payed"]); ?></td>
                        <td><?php echo ($region_list[$v['province_id']]); echo ($region_list[$v['city_id']]); echo ($region_list[$v['zone_id']]); ?>, <?php echo ($v["address"]); ?>, <?php echo ($v["zipcode"]); ?></td>
                        <td class="f16">
                            <a href="javascript:;" class="js-edit-item"><span class="glyphicon glyphicon-edit"></span></a>
                            <a href="javascript:;" class="js-remove-item"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
 
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"><button type="button" class="btn btn-success btn-xs" id="js-add-address">添加</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="tab-pane" id="pane4">
            <table class="table table-condensed table-bordered tb-data-list" id="js-address-list" data-reload-url="<?php echo U('MemberAddress/reloadaddress',array('mid'=>$data['id']));?>" data-add-url="<?php echo U('MemberAddress/add',array('mid'=>$data['id']));?>" data-edit-url="<?php echo U('MemberAddress/edit');?>" data-remove-url="<?php echo U('MemberAddress/delete');?>">
            <thead>
                    <tr>
                        <th class="th-120">姓名</th>
                        <th class="th-120">商品名</th>
                        <th class="th-120">商品分类</th>
                        <th class="th-120">OnSale</th>
                        <th class="th-120">SkuIofo</th>
                        <th class="th-120">原价</th>
                        <th class="th-120">售价</th>
                        <th class="th-120">stack</th> 
                        <th class="th-120">手机</th> 
                        <th class="th-120">是否付款</th> 
                        <th>地址</th>
                        <th class="th-120">操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(is_array($data["_stock_list"])): $i = 0; $__LIST__ = $data["_stock_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$b): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($b["id"]); ?>">
                        <td><?php echo ($b["account"]); ?></td>
                        <td><?php echo ($b["goods"]); ?></td>
                        <td><?php echo ($b["category"]); ?></td>
                        <td><?php echo ($b["is_onsale"]); ?></td>
                        <td><?php echo ($b["sku_info"]); ?></td>
                        <td><?php echo ($b["original_price"]); ?></td> 
                        <td><?php echo ($b["price"]); ?></td>
                        <td><?php echo ($b["stock"]); ?></td> 
                        <td><?php echo ($b["mobile"]); ?></td>
                        <td><?php echo ($b["is_payed"]); ?></td>
                        <td><?php echo ($region_list[$v['province_id']]); echo ($region_list[$v['city_id']]); echo ($region_list[$v['zone_id']]); ?>, <?php echo ($v["address"]); ?>, <?php echo ($v["zipcode"]); ?></td>
                        <td class="f16">
                            <a href="javascript:;" class="js-edit-item"><span class="glyphicon glyphicon-edit"></span></a>
                            <a href="javascript:;" class="js-remove-item"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><button type="button" class="btn btn-success btn-xs" id="js-add-address">添加</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    </div>


    
    <div class="tb-data-ctrl">
    <button type="submit" class="btn btn-primary"><?php echo L('SAVE');?></button>
    <button type="button" class="btn btn-default j-back"><?php echo L('BACK');?></button>
</div>
</form>
<div class='notifications top-right'></div>




    <script>
        $(function(){
            $.member.init();
        });
    </script>



    </body>
</html>