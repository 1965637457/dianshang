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



    <form action="" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
        <input type="hidden" name="code" value="<?php echo ($data["order_code"]); ?>" />
        <div class="container-fluid">
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
                            <?php if(is_array($data["_items"])): $i = 0; $__LIST__ = $data["_items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr data-skuid="<?php echo ($item["product_id"]); ?>">
                                    <td><img src="<?php echo ($item["_data"]["image"]); ?>" height="40" ></td>
                                    <td><?php echo ($item["product_id"]); ?></td>
                                    <td><?php echo ($item["goods_sno"]); ?></td>
                                    <td><?php echo ($item["_data"]["name"]); ?></td>
                                    <td><?php echo ($item["price"]); ?></td>
                                    <td><input type="text" name="items[<?php echo ($item["product_id"]); ?>]" value="<?php echo ($item["amount"]); ?>" class="form-control-sm t-xxs js-item" data-price="<?php echo ($item["price"]); ?>" ></td>
                                    <td><?php echo (number_format($item['price'] * $item['amount'],2)); ?></td>
                                    <td><a href="javascript:;" class="js-remove-item"><i class="glyphicon glyphicon-minus-sign red"></i></a></td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 pull-right">
                    <div class="panel panel-default">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="th-100">现产品金额</th>
                                    <td><input type="text" value="<?php echo ($data["cost_items"]); ?>" class="form-control input-sm" id="js-costItem" disabled></td>
                                </tr>
                                <tr>
                                    <th>原产品金额</th>
                                    <td><input type="text" value="<?php echo ($data["cost_items"]); ?>" class="form-control input-sm" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>





    <script>
        $.order.init();
    </script>



    </body>
</html>