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
<form action="<?php echo U('update');?>" method="post" enctype="multipart/form-data" class="form-data" id="j-fmData">
    <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
    
    
    <ul class="nav nav-tabs">
        <li class="active"><a data-target="#pane1" data-toggle="tab">基本信息</a></li>
        <li><a data-target="#pane2" data-toggle="tab">详细信息</a></li>
        <li><a data-target="#pane5" data-toggle="tab">扩展属性</a></li>
        <li><a data-target="#pane3" data-toggle="tab">产品图片</a></li>
        <li><a data-target="#pane4" data-toggle="tab">货品信息</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="pane1">
            <table class="table table-condensed tb-data">
                <tr>
                    <th><?php echo L('STATUS');?></th>
                    <td>
                        <label><input type="radio" name="info[status]" value="1" checked /> <?php echo L('ONSALE');?></label>
                        <label><input type="radio" name="info[status]" value="0" <?php if(($data["status"]) == "0"): ?>checked<?php endif; ?> /> <?php echo L('OFFSALE');?></label>
                        <?php if(($data["status"]) == "-1"): ?><label><input type="radio" name="info[status]" value="0" checked /> <?php echo L('RECYCLE');?></label><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo L('CATEGORY');?></th>
                    <td>
                        <select name="info[category_id]" class="form-control-sm" id="js-category-list">
                            <option value="0">-请选择-</option>
                            <?php if(is_array($categories)): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" data-type-id="<?php echo ($v["type_id"]); ?>" data-type="<?php echo ($type_list[$v['type_id']]['type_name']); ?>" <?php if(($data["category_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo (fn_sub_prefix($v["grade"])); echo ($v["category"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>	
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo L('VICE_CATE');?></th>
                    <td>
                        <select name="categories[]" multiple="multiple" class="js-multi-select">
                            <?php if(is_array($categories)): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(in_array(($v["id"]), is_array($data["categories"])?$data["categories"]:explode(',',$data["categories"]))): ?>selected<?php endif; ?>><?php echo (fn_sub_prefix($v["grade"])); echo ($v["category"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>选择商家</th>
                    <td>
                        <select name="info[business_id]" class="form-control-sm" id="js-category-list">
                            <option value="0" data-type-id="0" data-type="" >==请选择==</option>
                            <?php if(is_array($business)): $i = 0; $__LIST__ = $business;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($data["business_id"]) == $v["id"]): ?>selected="selected"<?php endif; ?>><?php echo ($v["business_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <i></i>
                    </td>
                </tr>
                <tr>
                    <th><?php echo L('CODE');?></th>
                    <td><input type="text" name="info[goods_sno]" value="<?php echo (htmlspecialchars($data["goods_sno"])); ?>" class="form-control-sm" /></td>
                </tr>
                <tr>
                    <th><?php echo L('NAME');?></th>
                    <td><input type="text" name="info[goods]" value="<?php echo (htmlspecialchars($data["goods"])); ?>" class="form-control-sm t-lg" /></td>
                </tr>
                <tr>
                    <th><?php echo L('VICE'); echo L('NAME');?></th>
                    <td><input type="text" name="info[short_name]" value="<?php echo (htmlspecialchars($data["short_name"])); ?>" class="form-control-sm t-lg" /></td>
                </tr>
                <tr>
                    <th>原价</th>
                    <td><input type="text" name="info[original_price]" value="<?php echo (htmlspecialchars($data["original_price"])); ?>" class="form-control-sm" /></td>
                </tr>
                <tr>
                    <th>售价</th>
                    <td><input type="text" name="info[price]" value="<?php echo (htmlspecialchars($data["price"])); ?>" class="form-control-sm" /></td>
                </tr>
                <tr>
                    <th><?php echo L('IMAGE');?></th>
                    <td><a href="javascript:;" data-url="<?php echo U('ajaxpickprimaryimage',array('id'=>$data['id']));?>" class="btn btn-success btn-xs" id="js-pickPrimaryImage">选择主图片</a></td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="info[image]" value="<?php echo ($data["image"]); ?>" id="js-primaryImageInput" >
                        <img src="<?php echo ($data["image"]); ?>" id="js-primaryImage" style="max-height: 200px;" />
                    </td>
                </tr>
            </table>
        </div>
        <div class="tab-pane" id="pane2">
            <table class="table table-condensed tb-data">
                <tr>
                    <th><?php echo L('CONTENT');?></th>
                    <td><textarea name="description[content]"><?php echo (htmlspecialchars($data["descriptions"]["content"])); ?></textarea></td>
                </tr>
            </table>
        </div>
        <div class="tab-pane" id="pane3" data-remove-url="<?php echo U('Goods/removeimage',array('goods_id'=>$data['id']));?>">
            <table class="table table-condensed tb-data">
                <tr>
                    <th>添加图片：</th>
                    <td>
                        <span class="btn btn-success btn-sm fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Add file...</span>
                            <input id="js-uploadimage" type="file" name="upload_file" data-url0="http://newproject.jacob/Debug/uploadimage" data-url="<?php echo U('Goods/uploadimage');?>" data-form-data='{ "goods_id": <?php echo ($data["id"]); ?> }' >
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ul class="goods-image-list" id="js-images">
                            <?php if(is_array($data["images"])): $i = 0; $__LIST__ = $data["images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
                                    <div><img src="<?php echo ($v["origin_image"]); ?>" height="100" ></div>
                                    <div>
                                        <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-left"></span></a>
                                        <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-right"></span></a>
                                        <a href="javascript:;" class="js-remove-image" data-url="<?php echo U('Goods/removeimage',array('goods_id'=>$v['goods_id'],'image_id'=>$v['image_id']));?>"><span class="glyphicon glyphicon-remove"></span></a>
                                    </div>
                                    <input type="hidden" name="images[]" value="<?php echo ($v["image_id"]); ?>" />
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div class="tab-pane" id="pane4" data-pick-url="<?php echo U('pickproductimage',array('id'=>$data['id']));?>" data-load-url="<?php echo U('loadproduct',array('id'=>$data['id']));?>" data-open-url="<?php echo U('openspecification',array('id'=>$data['id']));?>" data-close-url="<?php echo U('closespecification',array('id'=>$data['id']));?>" data-update-url="<?php echo U('setproductstatus');?>">
            <table class="table table-condensed tb-data">
    <tr>
        <th>货品规格</th>
        <td>
    <?php if(empty($data["specification"])): ?><input type="hidden" name="info[default_product]" value="<?php echo ($data["default_product"]); ?>" >
        <button type="button" class="btn btn-success btn-sm" id="js-open-specification">开启规格</button>
        <?php else: ?>
        <button type="button" class="btn btn-success btn-sm" id="js-open-specification">编辑规格</button>
        <button type="button" class="btn btn-danger btn-sm" id="js-close-specification">关闭规格</button><?php endif; ?>
</td>
</tr>
</table>
<?php if(empty($data["specification"])): ?><div style="padding:0 20px;">
        <table class="table table-condensed table-hover table-bordered tb-list">
            <thead>
                <tr>
                    <th style="width:100px;">Stock</th>
                </tr>
            </thead>
            <tbody>
            <?php if(is_array($data["products"])): $i = 0; $__LIST__ = $data["products"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($v["product_id"]); ?>">
                    <td><input type="text" name="products[<?php echo ($v["product_id"]); ?>][stock]" value="<?php echo ($v["stock"]); ?>" class="form-control-sm t-xs" ></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div style="padding:0 20px;">
        <table class="table table-condensed table-hover table-bordered tb-list">
            <thead>
                <tr>
                    <th style="width:80px;">Default</th>
                    <th style="width:80px;">OnSale</th>
                    <th style="width:100px;">SkuInfo</th>
                    <th style="width:200px;">ProductSno</th>
                    <th style="width:100px;">Stock</th>
                    <th>Images</th>
                </tr>
            </thead>
            <tbody>
            <?php if(is_array($data["products"])): $i = 0; $__LIST__ = $data["products"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($v["product_id"]); ?>">
                    <td><label><input type="radio" name="info[default_product]" value="<?php echo ($v["product_id"]); ?>" <?php if(($data["default_product"]) == $v["product_id"]): ?>checked<?php endif; ?> > #<?php echo ($v["product_id"]); ?></label></td>
                    <td class="f16"><a href="javascript:;" class="js-update-item" data-field="is_onsale" data-status="<?php echo ($v["is_onsale"]); ?>"><span class="glyphicon <?php echo ($v['is_onsale']==1?'glyphicon-ok-circle green':'glyphicon-remove-circle red'); ?>"></span></a></td>
                    <td><?php echo ($v["sku_info"]); ?></td>
                    <td><input type="text" name="products[<?php echo ($v["product_id"]); ?>][product_sno]" value="<?php echo ($v["product_sno"]); ?>" class="form-control-sm t-sm" ></td>
                    <td><input type="text" name="products[<?php echo ($v["product_id"]); ?>][stock]" value="<?php echo ($v["stock"]); ?>" class="form-control-sm t-xs" ></td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm js-pick-product-image fl" data-id="<?php echo ($v["product_id"]); ?>"><i class="glyphicon glyphicon-plus"></i></button>
                        <ul class="product-image-list">
                            <?php if(!empty($v["sku_images"])): $sku_images = explode(',', $v['sku_images']);;?>
                                <?php if(is_array($sku_images)): $z = 0; $__LIST__ = $sku_images;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($z % 2 );++$z;?><li>
                                        <div><img src="<?php echo ($data["images"]["$img"]["origin_image"]); ?>" height="30" ></div>
                                        <div>
                                            <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-left"></span></a>
                                            <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-right"></span></a>
                                            <a href="javascript:;" class="js-remove-image"><span class="glyphicon glyphicon-remove"></span></a>
                                        </div>
                                        <input type="hidden" name="products[<?php echo ($v["product_id"]); ?>][sku_images][]" value="<?php echo ($img); ?>" />
                                    </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                        </ul>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div><?php endif; ?>
        </div>
        <div class="tab-pane" id="pane5">
            <table class="table table-condensed tb-data" id="js-table-attribute">
                <thead>
                    <tr>
                        <th>产品类型</th>
                        <td>
                            <select name="info[type_id]" class="form-control-sm" id="js-type-list" data-url="<?php echo U('getattributes',array('goodsid'=>$data['id']));?>">
                                <option value="0">==无==</option>
                                <?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(($v["id"]) == $data["type_id"]): ?>selected<?php endif; ?>><?php echo ($v["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($attributes)): $i = 0; $__LIST__ = $attributes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attr): $mod = ($i % 2 );++$i;?><tr>
        <th><?php echo ($attr["attribute"]); ?></th>
        <td>
            <?php if(is_array($attr["_list"])): $i = 0; $__LIST__ = $attr["_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attr_val): $mod = ($i % 2 );++$i;?><label><input type="<?php echo ($attr['display']==1?'radio':'checkbox'); ?>" name="attrs[<?php echo ($attr["id"]); ?>][]" value="<?php echo ($attr_val["id"]); ?>" <?php if(in_array(($attr_val["id"]), is_array($chosen_attribute_ids)?$chosen_attribute_ids:explode(',',$chosen_attribute_ids))): ?>checked<?php endif; ?> > <?php echo ($attr_val["attribute_value"]); ?></label>&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="tb-data-ctrl">
    <button type="submit" class="btn btn-primary"><?php echo L('SAVE');?></button>
    <button type="button" class="btn btn-default j-back"><?php echo L('BACK');?></button>
</div>
</form>
<div class='notifications top-right'></div>




    <script src="/hamanaka3/Public/Admin/js/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <link href="/hamanaka3/Public/Admin/js/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" />

    <script src="/hamanaka3/Public/Admin/js/jquery-upload-file/vendor/jquery.ui.widget.js"></script>
    <script src="/hamanaka3/Public/Admin/js/jquery-upload-file/jquery.iframe-transport.js"></script>
    <script src="/hamanaka3/Public/Admin/js/jquery-upload-file/jquery.fileupload.js"></script>
    <link href="/hamanaka3/Public/Admin/js/jquery-upload-file/jquery.fileupload.css" rel="stylesheet" />
    <script>
        CKEDITOR.replace('description[content]');
        $('.js-multi-select').multiselect();
        $.goods.init();
    </script>



    </body>
</html>