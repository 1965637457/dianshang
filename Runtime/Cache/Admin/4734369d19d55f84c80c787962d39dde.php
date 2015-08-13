<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<form action="<?php echo U('insert');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    
    
    <input type="hidden" name="spec_id" value="<?php echo ($spec_id); ?>" />
    <table class="table table-condensed tb-data">
        <tr>
            <th>规格值</th>
            <td><input type="text" name="spec_value" class="form-control-sm" /></td>
        </tr>
        <tr>
            <th><?php echo L('IMAGE');?></th>
            <td><input type="file" name="upload_spec_image" /> <i><?php echo L('SIZE');?>：30*30px</i></td>
        </tr>
    </table>

</form>






    </body>
</html>