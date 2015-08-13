<?php if (!defined('THINK_PATH')) exit();?><table class="table table-condensed tb-data">
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