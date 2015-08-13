<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrator Panel</title>
        <link href="/Public/Admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/Public/Admin/style.css" rel="stylesheet">
        <link href="/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <script src="/Public/Admin/js/jquery.min.js"></script>
        <script src="/Public/Admin/bootstrap/js/bootstrap.min.js"></script>
        <script src="/Public/Admin/js/function.js"></script>
        <script src="/Public/Admin/js/myplugins.js"></script>
        <script src="/Public/Admin/js/common.js"></script>
        <script src="/Public/Admin/js/ckeditor/ckeditor.js"></script>
        <script src="/Public/Admin/js/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="/Public/Admin/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="/Public/Admin/js/zDialog/zDialog.min.js"></script>
        <!--[if lt IE 9]>
          <script src="/Public/Admin/bootstrap/js/html5shiv.min.js"></script>
          <script src="/Public/Admin/bootstrap/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

<nav class="navbar navbar-defined" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand"><img src="/Public/Admin/images/logo.png" /></a>
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
    
    
    <table class="table table-condensed tb-data">
        <tr>
            <th><?php echo L('STATUS');?></th>
            <td>
                <label><input type="radio" name="main[status]" value="1" checked /> <?php echo L('ACTIVE');?></label>
                <label><input type="radio" name="main[status]" value="0" <?php if(($data["status"]) == "0"): ?>checked<?php endif; ?> /> <?php echo L('DISABLED');?></label>
            </td>
        </tr>
        <tr>
            <th>优惠券规则</th>
            <td><input type="text" name="main[coupon_rule]" value="<?php echo (htmlspecialchars($data["coupon_rule"])); ?>" class="form-control-sm require" /></td>
        </tr>
        <tr>
            <th>规则别名</th>
            <td><input type="text" name="main[coupon_alias]" value="<?php echo (htmlspecialchars($data["coupon_alias"])); ?>" class="form-control-sm require" /> <i>前台展示</i></td>
        </tr>
        <tr>
            <th><?php echo L('START_TIME');?></th>
            <td>
                <input type="text" name="main[start_time]" value="<?php echo date('Y-m-d H:i:s', $data['start_time']);?>" class="form-control-sm js-form-datetime" data-date-format="yyyy-mm-dd hh:ii:ss" />
            </td>
        </tr>
        <tr>
            <th><?php echo L('END_TIME');?></th>
            <td>
                <input type="text" name="main[end_time]" value="<?php echo date('Y-m-d H:i:s', $data['end_time']);?>" class="form-control-sm js-form-datetime" data-date-format="yyyy-mm-dd hh:ii:ss" />
            </td>
        </tr>
        <tr>
            <th>优惠券类型</th>
            <td>
                <select name="main[coupon_type]" class="form-control-sm" id="js-coupon-type">
                    <?php if(is_array($coupon_types)): $i = 0; $__LIST__ = $coupon_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($data["coupon_type"]) == $key): ?>selected<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>最小消费金额</th>
            <td><input type="text" name="main[min_amount]" value="<?php echo (htmlspecialchars($data["min_amount"])); ?>" placeholder="0.00" class="form-control-sm require isNumber" /></td>
        </tr>
        <tr id="sectionDiscountAmount" <?php if(($data["coupon_type"]) != "1"): ?>class="hidden"<?php endif; ?>>
            <th>减免金额</th>
            <td><input type="text" name="main[discount_amount]" value="<?php echo (htmlspecialchars($data["discount_amount"])); ?>" placeholder="0.00" class="form-control-sm"/></td>
        </tr>
        <tr id="sectionDiscountRate" <?php if(($data["coupon_type"]) != "2"): ?>class="hidden"<?php endif; ?>>
            <th>折扣</th>
            <td><input type="text" name="main[discount_rate]" value="<?php echo (htmlspecialchars($data["discount_rate"])); ?>" placeholder="99" class="form-control-sm"/> <i>取值0 - 99</i></td>
        </tr>
        <tr>
            <th>发放目标</th>
            <td>
                <select name="main[target_type]" class="form-control-sm" id="js-target-type">
                    <?php if(is_array($target_types)): $i = 0; $__LIST__ = $target_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($data["target_type"]) == $key): ?>selected<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr id="sectionMember" class="hidden">
            <th>
                <a href="javascript:;" class="btn btn-success btn-xs" id="js-select-member" data-url="<?php echo U('members');?>">选择会员</a>
            </th>
            <td>
                <ul id="js-member-list" style="list-style: none;padding: 0;">
                    <?php if(is_array($data["_member_list"])): $i = 0; $__LIST__ = $data["_member_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li data-id="<?php echo ($v["id"]); ?>">
                            <input type="hidden" name="limit_members[]" value="<?php echo ($v["id"]); ?>" > 
                            <?php echo ($v["account"]); ?>
                            <a href="javascript:;" class="btn btn-danger btn-xs js-remove-item"><i class="glyphicon glyphicon-remove"></i></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </td>
        </tr>
        <tr id="sectionMemberLevel" class="hidden">
            <th>选择会员等级</th>
            <td>
                <ul style="height:150px;list-style: none;padding-left: 5px;border:1px solid #ccc;">
                    <?php if(is_array($member_levels)): $i = 0; $__LIST__ = $member_levels;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><label><input type="checkbox" name="limit_levels[]" value="<?php echo ($v["id"]); ?>" <?php if(in_array(($v["id"]), is_array($data['_chosen_member_level'])?$data['_chosen_member_level']:explode(',',$data['_chosen_member_level']))): ?>checked<?php endif; ?> > <?php echo ($v["member_level"]); ?></label></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th>限定类型</th>
            <td>
                <select name="main[limit_type]" class="form-control-sm" id="js-limit-type">
                    <?php if(is_array($limit_types)): $i = 0; $__LIST__ = $limit_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($data["limit_type"]) == $key): ?>selected<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr id="sectionProduct" <?php if(($data["limit_type"]) != "2"): ?>class="hidden"<?php endif; ?>>
            <th>
                <a href="javascript:;" class="btn btn-success btn-xs" id="js-select-product" data-url="<?php echo U('products');?>">选择商品</a>
            </th>
            <td>
                <ul id="js-limit-list" style="list-style: none;padding: 0;">
                    <?php if(is_array($data["_product_list"])): $i = 0; $__LIST__ = $data["_product_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li data-id="<?php echo ($v["id"]); ?>">
                            <input type="hidden" name="limit_products[]" value="<?php echo ($v["id"]); ?>" > 
                            <?php echo ($v["goods_sno"]); ?> | <?php echo ($v["goods"]); ?>
                            <a href="javascript:;" class="btn btn-danger btn-xs js-remove-item"><i class="glyphicon glyphicon-remove"></i></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </td>
        </tr>
        <tr id="sectionCategory" <?php if(($data["limit_type"]) != "3"): ?>class="hidden"<?php endif; ?>>
            <th>选择分类</th>
            <td>
                <ul style="height:150px;list-style: none;padding-left: 5px;border:1px solid #ccc;">
                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><label><input type="checkbox" name="limit_category[]" value="<?php echo ($v["id"]); ?>" <?php if(in_array(($v["id"]), is_array($data['_chosen_category'])?$data['_chosen_category']:explode(',',$data['_chosen_category']))): ?>checked<?php endif; ?> > <?php echo (do_sub_prefix($v["grade"])); echo ($v["category"]); ?></label></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th><?php echo L('REMARK');?></th>
            <td><textarea name="main[remark]" class="form-control-sm"><?php echo (htmlspecialchars($data["remark"])); ?></textarea></td>
        </tr>
    </table>

    
    <div class="tb-data-ctrl">
    <button type="submit" class="btn btn-primary"><?php echo L('SAVE');?></button>
    <button type="button" class="btn btn-default j-back"><?php echo L('BACK');?></button>
</div>
</form>
<div class='notifications top-right'></div>




    <script>
        $.coupon.initRule();
    </script>



    </body>
</html>