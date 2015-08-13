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



    <form action="" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
        <input type="hidden" name="code" value="<?php echo ($order["order_code"]); ?>" >
        <table class="table">
            <tbody>
                <tr>
                    <th>订单总额</th>
                    <td>￥<?php echo (number_format($order["total_amount"],2)); ?></td>
                </tr>
                <tr>
                    <th>支付时间</th>
                    <td><input type="text" name="pay_time" value="<?php echo date('Y-m-d H:i');?>" class="form-control-sm"></td>
                </tr>
                <tr>
                    <th>支付方式</th>
                    <td>
                        <select name="pay_method" class="form-control-sm">
                            <option value='1'>在线支付</option>
                            <option value='2'>银行转账</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>实付金额</th>
                    <td><input type="text" name="pay_amount" value="<?php echo ($order["total_amount"]); ?>" class="form-control-sm" ></td>
                </tr>
            </tbody>
        </table>
    </form>





    <script>
        $(function(){
            $('#j-fmData').bind('submit',function(){
                $.post(this.action,$(this).serialize(),function(data){
                    if(data.status===1){
                        var diag = Dialog.getInstance("popDialog");
                        diag.openerWindow.reload();
                    }else{
                        alert(data.info);
                    }
                },'json');
                return false;
            });
        });
    </script>



    </body>
</html>