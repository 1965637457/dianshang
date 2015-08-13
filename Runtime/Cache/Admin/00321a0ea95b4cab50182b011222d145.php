<?php if (!defined('THINK_PATH')) exit(); if(is_array($attributes)): $i = 0; $__LIST__ = $attributes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attr): $mod = ($i % 2 );++$i;?><tr>
        <th><?php echo ($attr["attribute"]); ?></th>
        <td>
            <?php if(is_array($attr["_list"])): $i = 0; $__LIST__ = $attr["_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attr_val): $mod = ($i % 2 );++$i;?><label><input type="<?php echo ($attr['display']==1?'radio':'checkbox'); ?>" name="attrs[<?php echo ($attr["id"]); ?>][]" value="<?php echo ($attr_val["id"]); ?>" <?php if(in_array(($attr_val["id"]), is_array($chosen_attribute_ids)?$chosen_attribute_ids:explode(',',$chosen_attribute_ids))): ?>checked<?php endif; ?> > <?php echo ($attr_val["attribute_value"]); ?></label>&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>