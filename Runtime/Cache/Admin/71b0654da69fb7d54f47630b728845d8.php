<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<form action="<?php echo U('insert');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    
    
    <input type="hidden" name="main[promotion_id]" value="<?php echo ($promotion_id); ?>" />
    <table class="table table-condensed tb-data">
        <tr>
            <th><?php echo L('STATUS');?></th>
            <td>
                <label><input type="radio" name="main[status]" value="1" checked /> <?php echo L('ACTIVE');?></label>
                <label><input type="radio" name="main[status]" value="0" /> <?php echo L('DISABLED');?></label>
            </td>
        </tr>
        <tr>
            <th>规则名称</th>
            <td><input type="text" name="main[promotion_rule]" class="form-control-sm require" /></td>
        </tr>
        <tr>
            <th>规则别名</th>
            <td><input type="text" name="main[promotion_rule_alias]" class="form-control-sm require" /> <i>前台展示</i></td>
        </tr>
        <tr>
            <th>促销类型</th>
            <td>
                <select name="main[promote_type]" class="form-control-sm" id="js-promote-type">
                    <?php if(is_array($promote_types)): $i = 0; $__LIST__ = $promote_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>最小消费金额</th>
            <td><input type="text" name="main[min_amount]" placeholder="0.00" class="form-control-sm require isNumber" /></td>
        </tr>
        <tr id="sectionDiscountAmount">
            <th>减免金额</th>
            <td><input type="text" name="main[discount_amount]" placeholder="0.00" class="form-control-sm"/></td>
        </tr>
        <tr id="sectionDiscountRate" class="hidden">
            <th>折扣</th>
            <td><input type="text" name="main[discount_rate]" placeholder="99" class="form-control-sm"/> <i>取值0 - 99</i></td>
        </tr>
        <tr>
            <th>限定类型</th>
            <td>
                <select name="main[limit_type]" class="form-control-sm" id="js-limit-type">
                    <?php if(is_array($limit_types)): $i = 0; $__LIST__ = $limit_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr id="sectionProduct" class="hidden">
            <th>
                <a href="javascript:;" class="btn btn-success btn-xs" id="js-select-product" data-url="<?php echo U('products');?>">选择商品</a>
            </th>
            <td>
                <ul id="js-limit-list" style="list-style: none;padding: 0;">
                    
                </ul>
            </td>
        </tr>
        <tr id="sectionCategory" class="hidden">
            <th>选择分类</th>
            <td>
                <ul style="height:150px;list-style: none;padding-left: 5px;border:1px solid #ccc;">
                    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><label><input type="checkbox" name="limit_category[]" value="<?php echo ($v["id"]); ?>" > <?php echo (do_sub_prefix($v["grade"])); echo ($v["category"]); ?></label></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th><?php echo L('REMARK');?></th>
            <td><textarea name="main[remark]" class="form-control-sm"></textarea></td>
        </tr>
    </table>

</form>




    <script>
        $.promotion.initRule();
    </script>



    </body>
</html>