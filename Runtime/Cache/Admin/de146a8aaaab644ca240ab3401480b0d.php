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
        <input type="hidden" name="code" value="<?php echo ($data["order_code"]); ?>" >
        <table class="table">
            <thead>
                <tr>
                    <td class="th-80"><b>开发票</b></td>
                    <td>
                        <label><input type="radio" name="need_invoice" value="1" checked /> 是</label>
                        <label><input type="radio" name="need_invoice" value="0" <?php if(($data["need_invoice"]) == "0"): ?>checked<?php endif; ?> /> 否</label>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>发票类型</th>
                    <td>
                        <label><input type="radio" name="invoice_type" value="1" checked /> 普通发票</label>
                        <label><input type="radio" name="invoice_type" value="2" <?php if(($data["invoice_type"]) == "2"): ?>checked<?php endif; ?> /> 增值税发票</label>
                    </td>
                </tr>
                <tr>
                    <th>发票抬头</th>
                    <td><input type="text" name="invoice_title" value="<?php echo (htmlspecialchars($data["invoice_title"])); ?>" class="form-control input-sm" /></td>
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