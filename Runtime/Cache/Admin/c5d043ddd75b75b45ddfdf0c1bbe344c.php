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



    <table class="table table-condensed table-hover tb-list" id="js-table-list">
        <thead>
            <tr>
                <th class="th-20"><input type="checkbox" class="j-check-all" /></th>
                <th class="th-40">ID</th>
                <th class="th-150"><?php echo L('NAME');?></th>
                <th class="th-150"><?php echo L('ALIAS');?></th>
                <th>属性值</th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                <td><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" data-attribute="<?php echo ($v["attribute"]); ?>" data-values="<?php echo ($v["attribute_values"]); ?>" /></td>
                <td><?php echo ($v["id"]); ?></td>
                <td><?php echo ((isset($v["attribute"]) && ($v["attribute"] !== ""))?($v["attribute"]):'== 未定义 =='); ?></td>
                <td><?php echo ($v["attribute_alias"]); ?></td>
                <td><?php echo ($v["attribute_values"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>







    </body>
</html>