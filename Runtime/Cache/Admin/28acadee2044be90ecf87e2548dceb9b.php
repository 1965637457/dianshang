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
<form action="<?php echo U('update');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
    
    
    <input type="hidden" name="member_id" value="<?php echo ($data["member_id"]); ?>" >
    <table class="table table-condensed tb-data">
        <tr>
            <th>默认</th>
            <td>
                <label><input type="radio" name="is_default" value="1" checked /> <?php echo L('ACTIVE');?></label>
                <label><input type="radio" name="is_default" value="0" <?php if(($data["is_default"]) == "0"): ?>checked<?php endif; ?> /> <?php echo L('DISABLED');?></label>
            </td>
        </tr>
        <tr>
            <th><?php echo L('CATEGORY');?></th>
            <td data-url="<?php echo U('MemberAddress/getregion');?>">
                <select name="province_id" class="form-control-sm js-province">
                    <option value="0">请选择省份...</option>
                    <?php if(is_array($province_list)): $i = 0; $__LIST__ = $province_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($data["province_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="city_id" class="form-control-sm js-city">
                    <option value="0">请选择城市...</option>
                    <?php if(is_array($city_list)): $i = 0; $__LIST__ = $city_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($data["city_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="zone_id" class="form-control-sm js-zone">
                    <option value="0">请选择区域...</option>
                    <?php if(is_array($zone_list)): $i = 0; $__LIST__ = $zone_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($data["zone_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php echo L('ADDRESS');?></th>
            <td><input type="text" name="address" value="<?php echo (htmlspecialchars($data["address"])); ?>" class="form-control-sm" /></td>
        </tr>
        <tr>
            <th><?php echo L('ZIPCODE');?></th>
            <td><input type="text" name="zipcode" value="<?php echo (htmlspecialchars($data["zipcode"])); ?>" class="form-control-sm" /></td>
        </tr>
        <tr>
            <th><?php echo L('FULLNAME');?></th>
            <td><input type="text" name="truename" value="<?php echo (htmlspecialchars($data["truename"])); ?>" class="form-control-sm" /></td>
        </tr>
        <tr>
            <th><?php echo L('PHONE');?></th>
            <td><input type="text" name="phone" value="<?php echo (htmlspecialchars($data["phone"])); ?>" class="form-control-sm" /></td>
        </tr>
        <tr>
            <th><?php echo L('MOBILE');?></th>
            <td><input type="text" name="mobile" value="<?php echo (htmlspecialchars($data["mobile"])); ?>" class="form-control-sm" /></td>
        </tr>
    </table>

</form>




    <script>
        $.region.init();
    </script>



    </body>
</html>