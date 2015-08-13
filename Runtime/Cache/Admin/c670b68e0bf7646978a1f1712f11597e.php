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

    <form action="<?php echo U('products');?>" method="get" class="list-search">
        <select name="category_id" class="form-control-sm">
            <option value="0">==选择分类==</option>
            <?php if(is_array($categories)): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($v["id"]) == $opts["category_id"]): ?>selected<?php endif; ?>><?php echo (fn_sub_prefix($v["grade"])); echo ($v["category"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <input type="text" name="kw" value="<?php echo ($opts["kw"]); ?>" class="form-control-sm" />
        <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i> <?php echo L('SEARCH');?></button>
    </form>



    <div class="table-responsive">
        <table class="table table-condensed table-hover tb-list">
            <thead>
                <tr>
                    <th class="th-20"><input type="checkbox" class="j-check-all" /></th>
                    <th class="th-40">ID</th>
                    <th><?php echo L('NAME');?></th>
                    <th class="th-150"><?php echo L('CODE');?></th>
                    <th class="th-150"><?php echo L('CATEGORY');?></th>
                </tr>
            </thead>
            <tbody id="js-products">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                    <td><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" data-sno="<?php echo ($v["goods_sno"]); ?>" data-name="<?php echo ($v["goods"]); ?>" /></td>
                    <td><?php echo ($v["id"]); ?></td>
                    <td><?php echo ((isset($v["goods"]) && ($v["goods"] !== ""))?($v["goods"]):'== 未定义 =='); ?></td>
                    <td><?php echo ($v["goods_sno"]); ?></td>
                    <td><?php echo ($v["category"]); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>







    </body>
</html>