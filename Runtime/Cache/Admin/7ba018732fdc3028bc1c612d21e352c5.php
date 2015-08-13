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
<form action="<?php echo U('insert');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    
    
    <input type="hidden" name="main[member_id]" value="<?php echo ($member["id"]); ?>" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">订单信息</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">订单状态</th>
                                <td>新订单</td>
                            </tr>
                            <tr>
                                <th>订单号</th>
                                <td>保存后自动生成</td>
                            </tr>
                            <tr>
                                <th>订单时间</th>
                                <td><input type="text" name="main[order_time]" value="<?php echo date('Y-m-d H:i:s');?>" class="form-control input-sm js-form-datetime " data-date-format="yyyy-mm-dd hh:ii:ss" /></td>
                            </tr>
                            <tr>
                                <th>订单IP</th>
                                <td><?php echo get_client_ip();?></td>
                            </tr>
                            <tr>
                                <th>订单备注</th>
                                <td><textarea name="main[notes]" class="form-control input-sm" placeholder="会员可见"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">会员信息</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">会员帐号</th>
                                <td><?php echo ($member["account"]); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">收货地址</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>会员地址</th>
                                <td>
                                    <select class="form-control input-sm" id="js-address-select" data-url="<?php echo U('MemberAddress/ajaxreloadregion');?>">
                                        <?php if(is_array($address_list)): $i = 0; $__LIST__ = $address_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($region_list[$v['province_id']]); echo ($region_list[$v['city_id']]); echo ($region_list[$v['zone_id']]); echo ($v["address"]); ?>,<?php echo ($v["zipcode"]); ?>,<?php echo ($v["truename"]); ?>,<?php echo ($v["mobile"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="th-80">收件人</th>
                                <td><input type="text" name="addr[truename]" value="<?php echo (htmlspecialchars($address["truename"])); ?>" class="form-control input-sm js-truename" /></td>
                            </tr>
                            <tr>
                                <th>省市区</th>
                                <td data-url="<?php echo U('MemberAddress/getregion');?>" id="js-region-section">
                                    <select name="addr[province_id]" class="form-control-sm js-province">
                                        <option value="0">请选择省份...</option>
                                        <?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($address["province_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <select name="addr[city_id]" class="form-control-sm js-city">
                                        <option value="0">请选择城市...</option>
                                        <?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($address["city_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <select name="addr[zone_id]" class="form-control-sm js-zone">
                                        <option value="0">请选择区域...</option>
                                        <?php if(is_array($zone_list)): $i = 0; $__LIST__ = $zone_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($address["zone_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>地址</th>
                                <td><input type="text" name="addr[address]" value="<?php echo (htmlspecialchars($address["address"])); ?>" class="form-control input-sm js-address" /></td>
                            </tr>
                            <tr>
                                <th>邮政编码</th>
                                <td><input type="text" name="addr[zipcode]" value="<?php echo (htmlspecialchars($address["zipcode"])); ?>" class="form-control input-sm js-zipcode" /></td>
                            </tr>
                            <tr>
                                <th>电话</th>
                                <td><input type="text" name="addr[phone]" value="<?php echo (htmlspecialchars($address["phone"])); ?>" class="form-control input-sm js-phone" /></td>
                            </tr>
                            <tr>
                                <th>手机</th>
                                <td><input type="text" name="addr[mobile]" value="<?php echo (htmlspecialchars($address["mobile"])); ?>" class="form-control input-sm js-mobile" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">支付信息</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">支付状态</th>
                                <td>未支付</td>
                            </tr>
                            <tr>
                                <th>支付方式</th>
                                <td>在线支付</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">发票信息</div>
                    <table class="table" id="js-order-invoice">
                        <thead>
                            <tr>
                                <td class="th-80"><b>开发票</b></td>
                                <td>
                                    <label><input type="radio" name="main[need_invoice]" value="1" /> 是</label>
                                    <label><input type="radio" name="main[need_invoice]" value="0" checked /> 否</label>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="hidden">
                            <tr>
                                <th>发票类型</th>
                                <td>
                                    <label><input type="radio" name="main[invoice_type]" value="1" checked /> 普通发票</label>
                                    <label><input type="radio" name="main[invoice_type]" value="2" /> 增值税发票</label>
                                </td>
                            </tr>
                            <tr>
                                <th>发票抬头</th>
                                <td><input type="text" name="main[invoice_title]" class="form-control input-sm" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        产品信息
                        <a href="javascript:;" data-url="<?php echo U('ajaxgetproducts');?>" id="js-add-item" class="pull-right"><i class="glyphicon glyphicon-plus"></i></a>
                    </div>
                    <table class="table" id="js-order-items">
                        <thead>
                            <tr>
                                <th class="th-100">产品图片</th>
                                <th class="th-80">SKUID</th>
                                <th class="th-100">产品编码</th>
                                <th>产品名称</th>
                                <th class="th-100">价格(元)</th>
                                <th class="th-100">数量</th>
                                <th class="th-100">小计</th>
                                <th class="th-40"></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <textarea name="main[remark]" class="form-control" placeholder="管理员备注"></textarea>
            </div>
            <div class="col-lg-3 pull-right">
                <div class="panel panel-default">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-100">产品金额</th>
                                <td><input type="text" value="0" class="form-control input-sm" id="js-costItem" disabled></td>
                            </tr>
                            <tr>
                                <th>运费</th>
                                <td class="input-group"><span class="input-group-addon">+</span><input type="text" name="main[cost_shipping]" value="0" class="form-control input-sm" id="js-costShip" ></td>
                            </tr>
                            <tr>
                                <th>减免金额</th>
                                <td class="input-group"><span class="input-group-addon">-</span><input type="text" name="main[discount]" value="0" class="form-control input-sm" id="js-discount" ></td>
                            </tr>
                            <tr>
                                <th>订单总额</th>
                                <td><input type="text" value="0" class="form-control input-sm" id="js-totalAmount" disabled></td>
                            </tr>
                            <tr>
                                <th>积分</th>
                                <td><input type="text" name="main[integral]" value="0" class="form-control input-sm" ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tb-data-ctrl">
    <button type="submit" class="btn btn-primary"><?php echo L('SAVE');?></button>
    <button type="button" class="btn btn-default j-back"><?php echo L('BACK');?></button>
</div>
</form>
<div class='notifications top-right'></div>




    <script>
        var address_list = <?php echo (json_encode($address_list)); ?>;
        $(".js-form-datetime").datetimepicker({
            todayBtn: true,
            todayHighlight: true,
            autoclose: true
        });
        $.order.init();
    </script>



    </body>
</html>