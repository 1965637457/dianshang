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



    <form action="<?php echo U('savespecification');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
        <input type="hidden" name="goods_id" value="<?php echo ($goods_id); ?>" >
        <div class="tab-left">
            <ul class="nav nav-tabs" id="js-spec-list">
                <?php if(is_array($spec_list)): $i = 0; $__LIST__ = $spec_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li <?php if(($i) == "1"): ?>class="active"<?php endif; ?>>
                    <label><input type="checkbox" name="specs[]" value="<?php echo ($v["id"]); ?>" <?php if(($v["_checked"]) == "1"): ?>checked<?php endif; ?> ></label>
                    <span>
                        <a href="javascript:;" class="js-move-up"><i class="glyphicon glyphicon-arrow-up"></i></a>
                        <a href="javascript:;" class="js-move-down"><i class="glyphicon glyphicon-arrow-down"></i></a>
                    </span>
                    <a data-target="#p<?php echo ($i); ?>" data-toggle="tab"><?php echo ($v["spec"]); ?></a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="tab-content" id="js-spec-value-list">
                <?php if(is_array($spec_list)): $i = 0; $__LIST__ = $spec_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-pane <?php if(($i) == "1"): ?>active<?php endif; ?>" id="p<?php echo ($i); ?>">
                        <table class="table table-condensed table-hover tb-list">
                            <thead>
                            <tr>
                                <th class="th-20"><input type="checkbox" class="j-check-all" /></th>
                                <th class="th-150">系统值</th>
                                <th class="th-150">自定义值</th>
                                <?php if(($v["display"]) == "0"): ?><th class="th-100">图像(30*30px)</th><?php endif; ?>
                                <th>排序</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($v["_list"])): $j = 0; $__LIST__ = $v["_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($j % 2 );++$j;?><tr>
                                <th>
                                    <input type="checkbox" name="spec_value_ids[<?php echo ($vv["spec_id"]); ?>][]" value="<?php echo ($vv["id"]); ?>" <?php if(($vv["_checked"]) == "1"): ?>checked<?php endif; ?> />
                                    <input type="hidden" name="spec_values[<?php echo ($vv["spec_id"]); ?>][<?php echo ($vv["id"]); ?>][id]" value="<?php echo ($vv["id"]); ?>" >
                                </th>
                                <th><?php echo ($vv["spec_value"]); ?></th>
                                <th><input type="text" name="spec_values[<?php echo ($vv["spec_id"]); ?>][<?php echo ($vv["id"]); ?>][pvt_val]" value="<?php echo (htmlspecialchars((isset($vv['pvt_val']) && ($vv['pvt_val'] !== ""))?($vv['pvt_val']):$vv['spec_value'])); ?>" class="form-control-sm t-xs" /></th>
                                <?php if(($v["display"]) == "0"): ?><th>
                                    <img src="<?php echo (htmlspecialchars((isset($vv['pvt_img']) && ($vv['pvt_img'] !== ""))?($vv['pvt_img']):$vv['spec_image'])); ?>" width="30" height="30" class="js-change-image">
                                    <input type="hidden" name="spec_values[<?php echo ($vv["spec_id"]); ?>][<?php echo ($vv["id"]); ?>][pvt_img]" value="<?php echo (htmlspecialchars((isset($vv['pvt_img']) && ($vv['pvt_img'] !== ""))?($vv['pvt_img']):'/hamanaka3/Uploads/'.$vv['spec_image'])); ?>" >
                                </th><?php endif; ?>
                                <th>
                                    <a href="javascript:;" class="js-move-up"><i class="glyphicon glyphicon-arrow-up"></i></a>
                                    <a href="javascript:;" class="js-move-down"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </form>





    <script>
        +function($){
            $.spec = {
                init : function(){
                    $('.js-change-image').bind('click',function(){
                        $.spec.el = $(this);
                        window.open("<?php echo U('Editor/elfinderforspecimage');?>", 'elfinder','height=450,z-look=1');
                    });
                    $('#j-fmData').bind('submit',function(){
                        var m = $("#js-spec-list :checked").length;
                        if(m < 1 || m > 2){
                            alert('请至少在左栏列表中选择1个规格，至多可以选择2个！');return false;
                        }
                        var n = $("#js-spec-value-list :checked").length;
                        if(n < 1){
                            alert('请在右栏列表中选择规格值！');return false;
                        }
                        return confirm("系统将更新产品规格信息，将有可能丢失现有的货品信息！您确定继续吗？");
                    });
                },
                setFile: function(file){
                    $.spec.el.attr('src', file).siblings(":hidden").val(file);
                }
            };
            $.spec.init();
        }(jQuery);
    </script>



    </body>
</html>