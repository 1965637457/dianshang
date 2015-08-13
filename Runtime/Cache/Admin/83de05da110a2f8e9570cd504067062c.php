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


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b class="panel-title">订单操作：</b>
                        <?php if(($data["order_status"]) == "1"): ?><button type="button" class="btn btn-warning btn-sm" id="js-payOrder" data-url="<?php echo U('payorder', array('code'=>$data['order_code']));?>">支付订单</button>
<button type="button" class="btn btn-danger btn-sm" id="js-cancelOrder" data-url="<?php echo U('cancelorder', array('code'=>$data['order_code']));?>">取消订单</button><?php endif; ?>
<?php if(($data["order_status"]) == "2"): ?><button type="button" class="btn btn-warning btn-sm" id="js-confirmOrder" data-url="<?php echo U('confirmorder', array('code'=>$data['order_code']));?>">确认支付</button><?php endif; ?>
<?php if(($data["order_status"]) == "3"): ?><button type="button" class="btn btn-default btn-sm disabled">已支付</button>
<button type="button" class="btn btn-primary btn-sm" id="js-shipOrder" data-url="<?php echo U('shiporder', array('code'=>$data['order_code']));?>">发货</button>
<button type="button" class="btn btn-warning btn-sm" id="js-cancelOrder" data-url="<?php echo U('cancelorder', array('code'=>$data['order_code']));?>">取消订单</button><?php endif; ?>
<?php if(($data["order_status"]) == "4"): ?><button type="button" class="btn btn-default btn-sm disabled">已支付</button>
<button type="button" class="btn btn-default btn-sm disabled">已发货</button>
<button type="button" class="btn btn-primary btn-sm" id="js-completeOrder" data-url="<?php echo U('completeorder', array('code'=>$data['order_code']));?>">完成订单</button><?php endif; ?>
<?php if(($data["order_status"]) == "5"): ?><button type="button" class="btn btn-default btn-sm disabled">已支付</button>
<button type="button" class="btn btn-default btn-sm disabled">已发货</button>
<button type="button" class="btn btn-default btn-sm disabled">交易完成</button>
<?php if(($data["total_amount"]) > $data["total_refund"]): ?><button type="button" class="btn btn-warning btn-sm" id="js-refundOrder" data-url="<?php echo U('refundorder', array('code'=>$data['order_code']));?>">退货/退款</button>
<?php else: ?>
<button type="button" class="btn btn-default btn-sm disabled">已退款</button><?php endif; endif; ?>
<?php if(($data["order_status"]) == "6"): ?><button type="button" class="btn btn-default btn-sm disabled">已取消</button><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">订单信息</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">订单状态</th>
                                <td><?php echo ($order_status_list[$data['order_status']]); ?></td>
                            </tr>
                            <tr>
                                <th>订单号</th>
                                <td><?php echo ($data["order_code"]); ?></td>
                            </tr>
                            <tr>
                                <th>订单时间</th>
                                <td><?php echo date('Y-m-d H:i:s', $data['order_time']);?></td>
                            </tr>
                            <tr>
                                <th>订单IP</th>
                                <td><?php echo ($data["order_ip"]); ?></td>
                            </tr>
                            <tr>
                                <th>订单备注</th>
                                <td><?php echo ($data["notes"]); ?></td>
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
                                <td><a href="<?php echo U('Member/edit',array('id'=>$data['member_id']));?>"><?php echo ($data["_member"]["account"]); ?></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        收货地址
                        <?php if(in_array(($data["order_status"]), explode(',',"1,2,3"))): ?><a href="javascript:;" class="pull-right" id="js-editAddr" data-url="<?php echo U('ajaxeditaddress',array('code'=>$data['order_code']));?>"><i class="glyphicon glyphicon-edit"></i></a><?php endif; ?>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">收件人</th>
                                <td><?php echo ($data["_addr"]["truename"]); ?></td>
                            </tr>
                            <tr>
                                <th>地址</th>
                                <td>
                                    <?php echo ($region_list[$data['_addr']['province_id']]); echo ($region_list[$data['_addr']['city_id']]); echo ($region_list[$data['_addr']['zone_id']]); echo ($data["_addr"]["address"]); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>邮政编码</th>
                                <td><?php echo ($data["_addr"]["zipcode"]); ?></td>
                            </tr>
                            <tr>
                                <th>电话</th>
                                <td><?php echo ($data["_addr"]["phone"]); ?></td>
                            </tr>
                            <tr>
                                <th>手机</th>
                                <td><?php echo ($data["_addr"]["mobile"]); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if(in_array(($data["order_status"]), explode(',',"4,5"))): ?><div class="panel panel-default">
                    <div class="panel-heading">
                        发货信息
                        <?php if(($data["order_status"]) == "4"): ?><a href="javascript:;" class="pull-right" id="js-editExpress" data-url="<?php echo U('ajaxeditexpress',array('code'=>$data['order_code']));?>"><i class="glyphicon glyphicon-edit"></i></a><?php endif; ?>
                    </div>		
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">快递方式</th>
                                <td><?php echo ((isset($express_list[$data['express_id']]) && ($express_list[$data['express_id']] !== ""))?($express_list[$data['express_id']]):"-"); ?></td>
                            </tr>
                            <tr>
                                <th class="th-80">快递单号</th>
                                <td><?php echo ((isset($data["express_bill"]) && ($data["express_bill"] !== ""))?($data["express_bill"]):'-'); ?></td>
                            </tr>
                            <tr>
                                <th class="th-80">发货时间</th>
                                <td><?php echo (fn_date('Y-m-d H:i',$data["ship_time"])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div><?php endif; ?>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">支付信息</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-80">支付状态</th>
                                <td><?php if(($data["is_paid"]) == "0"): ?>未支付<?php else: ?>已支付<?php endif; ?></td>
                            </tr>
                            <?php if(($data["is_paid"]) == "1"): ?><tr>
                                <th>支付方式</th>
                                <td>在线支付</td>
                            </tr>
                            <tr>
                                <th>实付金额</th>
                                <td>￥<?php echo (number_format($data["pay_amount"],2)); ?></td>
                            </tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        发票信息
                        <?php if(in_array(($data["order_status"]), explode(',',"1,2,3"))): ?><a href="javascript:;" class="pull-right" id="js-editInvoice" data-url="<?php echo U('ajaxeditinvoice',array('code'=>$data['order_code']));?>"><i class="glyphicon glyphicon-edit"></i></a><?php endif; ?>
                    </div>
                    <table class="table" id="js-order-invoice">
                        <thead>
                            <tr>
                                <td class="th-80"><b>开发票</b></td>
                                <td><?php if(($data["need_invoice"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></td>
                            </tr>
                        </thead>
                        <?php if(($data["need_invoice"]) == "1"): ?><tbody>
                            <tr>
                                <th>发票类型</th>
                                <td><?php if(($data["invoice_type"]) == "1"): ?>普通发票<?php else: ?>增值税发票<?php endif; ?></td>
                            </tr>
                            <tr>
                                <th>发票抬头</th>
                                <td><?php echo ($data["invoice_title"]); ?></td>
                            </tr>
                        </tbody><?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        产品信息
                        
                        <?php if(in_array(($data["order_status"]), explode(',',"1,2,3"))): ?><a href="javascript:;" data-url="<?php echo U('ajaxeditproducts',array('code'=>$data['order_code']));?>" id="js-editItems" class="pull-right"><i class="glyphicon glyphicon-edit"></i></a><?php endif; ?>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($data["_items"])): $i = 0; $__LIST__ = $data["_items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr>
                                    <td><img src="<?php echo ($item["_data"]["image"]); ?>" height="40" ></td>
                                    <td><?php echo ($item["product_id"]); ?></td>
                                    <td><?php echo ($item["goods_sno"]); ?></td>
                                    <td><?php echo ($item["_data"]["name"]); ?></td>
                                    <td><?php echo C('DOLLAR'); echo ($item["price"]); ?></td>
                                    <td><?php echo ($item["amount"]); ?></td>
                                    <td><?php echo C('DOLLAR'); echo (number_format($item['price'] * $item['amount'],2)); ?></td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4">
                <textarea name="main[remark]" class="form-control" id="js-editRemark" data-url="<?php echo U('ajaxeditremark',array('code'=>$data['order_code']));?>" placeholder="管理员备注"><?php echo (htmlspecialchars($data["remark"])); ?></textarea>
            </div>
            <div class="col-lg-3 pull-right">
                <div class="panel panel-default">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="th-100">产品金额</th>
                                <td><input type="text" value="<?php echo ($data["cost_items"]); ?>" class="form-control input-sm" id="js-costItem" disabled></td>
                            </tr>
                            
                            <?php if(in_array(($data["order_status"]), explode(',',"1,2,3"))): ?><tr>
                                <th>运费</th>
                                <td class="input-group"><span class="input-group-addon">+</span><input type="text" value="<?php echo ($data["cost_shipping"]); ?>" class="form-control input-sm"><a href="javascript:;" class="input-group-addon js-modifyCost" data-url="<?php echo U('ajaxeditcostship',array('code'=>$data['order_code']));?>"><i class="glyphicon glyphicon-refresh"></i></a></td>
                            </tr>
                            <tr>
                                <th>减免金额</th>
                                <td class="input-group"><span class="input-group-addon">-</span><input type="text" value="<?php echo ($data["discount"]); ?>" class="form-control input-sm"><a href="javascript:;" class="input-group-addon js-modifyCost" data-url="<?php echo U('ajaxeditdiscount',array('code'=>$data['order_code']));?>"><i class="glyphicon glyphicon-refresh"></i></a></td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <th>运费</th>
                                <td class="input-group"><span class="input-group-addon">+</span><input type="text" value="<?php echo ($data["cost_shipping"]); ?>" class="form-control input-sm" disabled></td>
                            </tr>
                            <tr>
                                <th>减免金额</th>
                                <td class="input-group"><span class="input-group-addon">-</span><input type="text" value="<?php echo ($data["discount"]); ?>" class="form-control input-sm" disabled></td>
                            </tr><?php endif; ?>
                            <tr>
                                <th>订单总额</th>
                                <td><input type="text" value="<?php echo ($data["total_amount"]); ?>" class="form-control input-sm" id="js-totalAmount" disabled></td>
                            </tr>
                            <tr>
                                <th>积分</th>
                                <td><input type="text" value="<?php echo ($data["integral"]); ?>" class="form-control input-sm" disabled ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">退款记录</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="th-150"><b>退款时间</b></td>
                                <td class="th-120"><b>退款金额</b></td>
                                <td><b>退款项目</b></td>
                                <td><b>退款备注</b></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($refund_list)): $i = 0; $__LIST__ = $refund_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></td>
                                <td><?php echo ($v["refund"]); ?></td>
                                <td>
                                <?php if(is_array($v["_items"])): $i = 0; $__LIST__ = $v["_items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><p>[<?php echo ($item["_extra"]["goods_sno"]); ?>]<?php echo ($item["_extra"]["name"]); ?>[<?php echo ($item["_extra"]["sku_info"]); ?>] X <?php echo ($item["amount"]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                                <td><?php echo ($v["remark"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">操作日志</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="th-150"><b>操作时间</b></td>
                                <td class="th-120"><b>操作人</b></td>
                                <td><b>操作项目</b></td>
                                <td><b>操作备注</b></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($log_list)): $i = 0; $__LIST__ = $log_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></td>
                                <td><?php echo ($v["handler"]); ?></td>
                                <td><?php echo ($v["notes"]); ?></td>
                                <td><?php echo ($v["remark"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<div class='notifications top-right'></div>




    <script>
        $.order.init();
    </script>



    </body>
</html>