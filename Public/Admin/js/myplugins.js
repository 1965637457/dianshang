/* 
 * 规格模块
 */
$.spec = {};
$.spec.init = function(){
    var $obj = $('#js-spec-value-list');
    $obj.on('click','#js-add-item',function(){
        var url = $obj.attr('data-add-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '添加';
        diag.Height = 150;
        diag.OKEvent = function(){
            diag.innerDoc.getElementById('j-fmData').submit();
        };
        diag.show();
    }).on('click','.js-edit-item',function(){
        var $o = $(this).parent().parent();
        var url = $obj.attr('data-edit-url') + '/id/' + $o.attr('data-id');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '编辑';
        diag.Height = 200;
        diag.OKEvent = function(){
            diag.innerDoc.getElementById('j-fmData').submit();
        };
        diag.show();
    }).on('click','.js-remove-item',function(){
        if(confirm('确认删除吗？')){
            var $o = $(this).parent().parent();
            var url = $obj.attr('data-remove-url');
            $.getJSON(url,{ id : $o.attr('data-id') },function(data){
                if(data.status===0){
                    alert(data.info);
                }else{
                    $o.remove();
                }
            });
        }
    });
};
$.spec.createItem = function(data){
    var $obj = $('#js-spec-value-list');
    var html = $.spec.formatItem(data);
    $('tbody',$obj).append(html);
};
$.spec.updateItem = function(data){
    var $obj = $('#js-spec-value-list');
    var html = $.spec.formatItem(data);
    $o = $("tr[data-id='"+data.id+"']", $obj);
    $o.after(html).remove();
};
$.spec.formatItem = function(data){
    var html = '<tr data-id="'+data.id+'">\
        <td>'+ data.spec_value +'</td>\
        <td><img src="'+data.spec_image+'" width="30" height="30"></td>\
        <td class="f16">\
            <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-up"></span></a>\
            <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-down"></span></a>\
            <a href="javascript:;" class="js-edit-item"><span class="glyphicon glyphicon-edit"></span></a>\
            <a href="javascript:;" class="js-remove-item"><span class="glyphicon glyphicon-remove"></span></a>\
            <input type="hidden" name="spec_values[]" value="'+data.id+'">\
        </td>\
    </tr>';
    return html;
};
/* 
 * 产品模块
 */
$.goods = {};
$.goods.init = function() {
    //产品分类发生变化
    $('#js-category-list').bind('change', function() {
        $cate_selected = $(this).find("option:selected");
        var type_name = $cate_selected.attr('data-type');
        var type_id = $cate_selected.attr('data-type-id');
        var info = type_name === '' ? '' : "关联了产品类型：" + type_name + "，您可以在扩展属性中编辑更多信息";
        var info2 = type_name === '' ? '' : '推荐关联类型：' + type_name;
        $(this).siblings('i').text(info);

        $type = $('#js-type-list');
        $type.siblings('i').text(info2);
        $type.val(type_id);
        $type.trigger('change');
    });
    //产品类型发生变化
    $('#js-type-list').bind('change', function() {
        if (this.value === '0') {
            $('#js-table-attribute tbody').html('');
        } else {
            var url = $(this).attr('data-url');
            $.getJSON(url, {typeid: this.value}, function(data) {
                if (data.status === 1) {
                    $('#js-table-attribute tbody').html(data.info);
                } else {
                    $('#js-table-attribute tbody').html('');
                }
            });
        }
    });
    //产品图片
    var $imageSection = $('#pane3');
    $('#js-uploadimage').fileupload({
        dataType: 'json',
        acceptFileTypes: "/(\.|\/)(gif|jpe?g|png)$/i",
        maxFileSize: 2000000,
        done: function(e, data) {
            if (data.result.status === 0) {
                alert(data.result.info);
            } else {
                var li = '<li data-image-id="' + data.result.id + '">\
                            <div><img src="' + data.result.src + '" height="100" ></div>\
                            <div>\
                                <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-left"></span></a>\
                                <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-right"></span></a>\
                                <a href="javascript:;" class="js-remove-image"><span class="glyphicon glyphicon-remove"></span></a>\
                            </div>\
                            <input type="hidden" name="images[]" value="' + data.result.id + '" />\
                        </li>';
                $('#js-images').append(li);
            }
        }
    });
    $imageSection.on('click', '.js-remove-image', function() {
        var url = $imageSection.attr('data-remove-url');
        if (confirm('确认删除吗？')) {
            var $li = $(this).parent().parent();
            var image_id = $li.attr('data-id');
            $.getJSON(url, {image_id: image_id}, function(data) {
                if (data.status === 0) {
                    alert(data.info);
                } else {
                    $li.remove();
                }
            });
        }
    });
    //挑选产品主图片
    $("#js-pickPrimaryImage").bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '选择产品主图片';
        diag.Width = $(window).width() - 200;
        diag.Height = $(window).height() - 250;
        diag.OKEvent = function() {
            var src = diag.innerWin.$('#js-images :checked').attr('data-src');
            $('#js-primaryImage').attr('src', src);
            $('#js-primaryImageInput').val(src);
            diag.close();
        };
        diag.show();
    });
    //货品信息
    var $productContainer = $('#pane4');
    $productContainer.on('click', '#js-open-specification', function() {
        var url = $productContainer.attr('data-open-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '选择规格';
        diag.Message = '一、左栏中选择至多两个规格；<br/>二、右栏中选择相应规格的规格值，可自定义显示的规格值及图像';
        diag.Width = $(window).width() - 200;
        diag.Height = $(window).height() - 250;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    }).on('click', '#js-close-specification', function() {
        if (confirm('关闭规格将导致所有货品信息全部丢失！您确定要继续吗？')) {
            var url = $productContainer.attr('data-close-url');
            $.getJSON(url, function(data) {
                if (data.status === 1) {
                    $.goods.loadProduct();
                } else {
                    alert(data.info);
                }
            });
        }
    }).on('click', '.js-update-item', function() {
        var $el = $(this);
        var $o = $(this).parent().parent();
        var url = $productContainer.attr('data-update-url');
        var id = $o.attr('data-id'), field = $el.attr('data-field'), val = $el.attr('data-status');
        $.getJSON(url, {id: id, field: field, val: val}, function(data) {
            if (data.status === 0) {
                alert("请刷新后再试！");
            } else {
                var classes = data.val === 1 ? 'glyphicon glyphicon-ok-circle green' : 'glyphicon glyphicon-remove-circle red';
                $el.attr('data-status', data.val);
                $('span', $el).removeClass().addClass(classes);
            }
        });
    }).on('click', '.js-pick-product-image', function() {
        var url = $productContainer.attr('data-pick-url');
        var $con = $(this).siblings('ul');
        var productID = $(this).attr('data-id');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '选择图片';
        diag.Width = $(window).width() - 200;
        diag.Height = $(window).height() - 250;
        diag.OKEvent = function() {
            diag.innerWin.$('#js-images :checked').each(function() {
                var li = '<li>\
                                <div><img src="' + $(this).attr('data-src') + '" height="30" ></div>\
                                <div>\
                                    <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-left"></span></a>\
                                    <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-right"></span></a>\
                                    <a href="javascript:;" class="js-remove-image"><span class="glyphicon glyphicon-remove"></span></a>\
                                </div>\
                                <input type="hidden" name="products[' + productID + '][sku_images][]" value="' + $(this).val() + '" />\
                            </li>';
                $con.append(li);
            });
            diag.close();
        };
        diag.show();
    }).on('click', '.js-remove-image', function() {
        $o = $(this).parent().parent();
        $o.remove();
    });
};
$.goods.loadProduct = function() {
    var $productContainer = $('#pane4');
    var url = $productContainer.attr('data-load-url');
    $productContainer.load(url);
}
/* 
 * 促销活动模块
 */
$.promotion = {};
$.promotion.init = function(){
    $ruleContainer = $('#js-promotion-rule-list');
    $ruleContainer.on('click','#js-add-item',function(){
        var url = $ruleContainer.attr("data-add-url");
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '添加';
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    }).on('click', '.js-edit-item', function() {
        var $o = $(this).parent().parent();
        var url = $ruleContainer.attr("data-edit-url") + '/id/' + $o.attr('data-id');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '编辑';
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    }).on('click', '.js-update-item', function() {
        var $el = $(this);
        var $o = $(this).parent().parent();
        var url = $ruleContainer.attr('data-update-url');
        var id = $o.attr('data-id'), field = $el.attr('data-field'), val = $el.attr('data-status');
        $.getJSON(url, {id: id, field: field, val: val}, function(data) {
            if (data.status === 0) {
                alert("请刷新后再试！");
            } else {
                var classes = data.val === 1 ? 'glyphicon glyphicon-ok-circle green' : 'glyphicon glyphicon-remove-circle red';
                $el.attr('data-status', data.val);
                $('span', $el).removeClass().addClass(classes);
            }
        });
    }).on('click', '.js-remove-item', function() {
        if (confirm('确认删除吗？')) {
            var $o = $(this).parent().parent();
            var url = $ruleContainer.attr("data-remove-url");
            $.getJSON(url, { id: $o.attr('data-id') }, function(data) {
                if (data.status === 0) {
                    alert(data.info);
                } else {
                    $o.remove();
                }
            });
        }
    });
};
$.promotion.initRule = function(){
    $('#j-fmData').bind('submit',function(){
        if(!checkRequire(this)){
            return false;
        }
        var limit_type = $('#js-limit-type').val();
        if(limit_type === "2" && $('#sectionProduct :hidden').length < 1){
            alert('请选择指定的商品！');
            return false;
        }else if(limit_type === "3" && $('#sectionCategory :checked').length < 1){
            alert('请选择指定的商品分类！');
            return false;
        }
    });
    $('#js-promote-type').bind('change', function(){
        var promote_type = this.value;
        if(promote_type==='2'){
            $('#sectionDiscountAmount').addClass('hidden');
            $('#sectionDiscountRate').removeClass('hidden');
        }else{
            $('#sectionDiscountAmount').removeClass('hidden');
            $('#sectionDiscountRate').addClass('hidden');
        }
    });
    $('#js-limit-type').bind('change', function(){
        var limit_type = this.value;
        if(limit_type==='2'){
            $('#sectionProduct').removeClass('hidden');
            $('#sectionCategory').addClass('hidden');
        }else if(limit_type==='3'){
            $('#sectionProduct').addClass('hidden');
            $('#sectionCategory').removeClass('hidden');
        }else{
            $('#sectionProduct, #sectionCategory').addClass('hidden');
        }
    });
    $('#js-select-product').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popProduct';
        diag.URL = url;
        diag.Title = '选择产品';
        diag.Width = 800;
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            $con = $('#js-limit-list');
            diag.innerWin.$('#js-products :checked').each(function() {
                if($con.find('li[data-id="'+this.value+'"]').length > 0){
                    return;
                }
                var li = '<li data-id="'+this.value+'">\
                        <input type="hidden" name="limit_products[]" value="'+this.value+'" >\
                        '+$(this).attr('data-sno') + ' | ' + $(this).attr('data-name') +'\
                        <a href="javascript:;" class="btn btn-danger btn-xs js-remove-item"><i class="glyphicon glyphicon-remove"></i></a>\
                    </li>';
                $con.append(li);
            });
            diag.close();
        };
        diag.show();
    });
    $('#js-limit-list').on('click', '.js-remove-item', function(){
        $(this).parent().remove();
    });
    $('#js-promote-type, #js-limit-type').trigger('change');
}
$.promotion.formatItem = function(data){
    var html = '<tr data-id="'+data.id+'">\
                    <td>'+data.name+'</td>\
                    <td>'+data.alias+'</td>\
                    <td>'+data.min_amount+'</td>\
                    <td>'+data.promote_type+'</td>\
                    <td>'+data.discount+'</td>\
                    <td>'+data.limit_type+'</td>\
                    <td class="f16">\
                        <a href="javascript:;" class="js-move-up"><span class="glyphicon glyphicon-arrow-up"></span></a>\
                        <a href="javascript:;" class="js-move-down"><span class="glyphicon glyphicon-arrow-down"></span></a>\
                        <a href="javascript:;" class="js-edit-item"><span class="glyphicon glyphicon-edit"></span></a>\
                        <a href="javascript:;" class="js-remove-item"><span class="glyphicon glyphicon-remove"></span></a>\
                        <input type="hidden" name="promotion_rules[]" value="'+data.id+'">\
                    </td>\
                </tr>';
    return html;
}
$.promotion.createItem = function(data){
    var $ruleContainer = $('#js-promotion-rule-list tbody');
    var html = $.promotion.formatItem(data);
    $ruleContainer.append(html);
}
$.promotion.updateItem = function(data){
    var $ruleContainer = $('#js-promotion-rule-list tbody');
    var html = $.promotion.formatItem(data);
    var $o = $("tr[data-id='"+data.id+"']", $ruleContainer);
    $o.after(html).remove();
}
/* 
 * 优惠券模块
 */
$.coupon = {};
$.coupon.initRule = function(){
    $('#j-fmData').bind('submit',function(){
        if(!checkRequire(this)){
            return false;
        }
        var target_type = $('#js-target-type').val();
        if(target_type === "2" && $('#sectionMember :hidden').length < 1){
            alert('请选择指定的会员！');
            return false;
        }else if(target_type === "3" && $('#sectionMemberLevel :checked').length < 1){
            alert('请选择指定的会员等级！');
            return false;
        }
        var limit_type = $('#js-limit-type').val();
        if(limit_type === "2" && $('#sectionProduct :hidden').length < 1){
            alert('请选择指定的商品！');
            return false;
        }else if(limit_type === "3" && $('#sectionCategory :checked').length < 1){
            alert('请选择指定的商品分类！');
            return false;
        }
    });
    $('#js-coupon-type').bind('change', function(){
        var coupon_type = this.value;
        if(coupon_type==='2'){
            $('#sectionDiscountAmount').addClass('hidden');
            $('#sectionDiscountRate').removeClass('hidden');
        }else{
            $('#sectionDiscountAmount').removeClass('hidden');
            $('#sectionDiscountRate').addClass('hidden');
        }
    });
    $('#js-target-type').bind('change', function(){
        var target_type = this.value;
        if(target_type==='2'){
            $('#sectionMember').removeClass('hidden');
            $('#sectionMemberLevel').addClass('hidden');
        }else if(target_type==='3'){
            $('#sectionMember').addClass('hidden');
            $('#sectionMemberLevel').removeClass('hidden');
        }else{
            $('#sectionMember, #sectionMemberLevel').addClass('hidden');
        }
    });
    $('#js-limit-type').bind('change', function(){
        var limit_type = this.value;
        if(limit_type==='2'){
            $('#sectionProduct').removeClass('hidden');
            $('#sectionCategory').addClass('hidden');
        }else if(limit_type==='3'){
            $('#sectionProduct').addClass('hidden');
            $('#sectionCategory').removeClass('hidden');
        }else{
            $('#sectionProduct, #sectionCategory').addClass('hidden');
        }
    });
    $('#js-select-product').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popProduct';
        diag.URL = url;
        diag.Title = '选择产品';
        diag.Width = 800;
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            $con = $('#js-limit-list');
            diag.innerWin.$('#js-products :checked').each(function() {
                if($con.find('li[data-id="'+this.value+'"]').length > 0){
                    return;
                }
                var li = '<li data-id="'+this.value+'">\
                        <input type="hidden" name="limit_products[]" value="'+this.value+'" >\
                        '+$(this).attr('data-sno') + ' | ' + $(this).attr('data-name') +'\
                        <a href="javascript:;" class="btn btn-danger btn-xs js-remove-item"><i class="glyphicon glyphicon-remove"></i></a>\
                    </li>';
                $con.append(li);
            });
            diag.close();
        };
        diag.show();
    });
    $('#js-select-member').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popMember';
        diag.URL = url;
        diag.Title = '选择会员';
        diag.Width = 800;
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            $con = $('#js-member-list');
            diag.innerWin.$('#js-members :checked').each(function() {
                if($con.find('li[data-id="'+this.value+'"]').length > 0){
                    return;
                }
                var li = '<li data-id="'+this.value+'">\
                        <input type="hidden" name="limit_members[]" value="'+this.value+'" >\
                        '+ $(this).attr('data-name') +'\
                        <a href="javascript:;" class="btn btn-danger btn-xs js-remove-item"><i class="glyphicon glyphicon-remove"></i></a>\
                    </li>';
                $con.append(li);
            });
            diag.close();
        };
        diag.show();
    });
    $('#js-limit-list').on('click', '.js-remove-item', function(){
        $(this).parent().remove();
    });
    $('#js-coupon-type, #js-target-type, #js-limit-type').trigger('change');
};
/* 
 * 会员模块
 */
$.member = {};
$.member.init = function(){
    var $container = $('#js-address-list');
    $container.on('click','#js-add-address',function(){
        var url = $container.attr("data-add-url");
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '添加';
        diag.Width = 800;
        diag.Height = 350;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    }).on('click', '.js-edit-item', function() {
        var $o = $(this).parent().parent();
        var url = $container.attr("data-edit-url") + '/id/' + $o.attr('data-id');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '编辑';
        diag.Width = 800;
        diag.Height = 350;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    }).on('click', '.js-remove-item', function() {
        if (confirm('确认删除吗？')) {
            var $o = $(this).parent().parent();
            var url = $container.attr("data-remove-url");
            $.getJSON(url, { id: $o.attr('data-id') }, function(data) {
                if (data.status === 0) {
                    alert(data.info);
                } else {
                    $o.remove();
                }
            });
        }
    });
};
$.member.reloadAddress = function(){
    var $container = $('#js-address-list');
    var url = $container.attr('data-reload-url');
    $.getJSON(url,function(data){
        if(data.status===0){
            alert(data.info);
        }else{
            $container.find('tbody').html(data.html);
        }
    });
};
/* 
 * 区域模块
 */
$.region = {};
$.region.init = function(){
    $(document).on('change','select.js-province',function(){
        $this = $(this);
        var url = $this.parent().attr('data-url');
        $city = $this.siblings('.js-city');
        $zone = $this.siblings('.js-zone');
        $city.children("option:gt(0)").remove();
        $zone.children("option:gt(0)").remove();
        if(this.value!=='0'){
            $.post(url, { pid: this.value }, function(data){
                if(data.status===0){
                    alert(data.info);
                }else{
                    $.each(data.list,function(i,o){
                        $city.append('<option value="'+o.id+'">'+o.region+'</option>');
                    });
                }
            },'json');
        }
    });
    $(document).on('change','select.js-city',function(){
        $this = $(this);
        var url = $this.parent().attr('data-url');
        $zone = $this.siblings('.js-zone');
        $zone.children("option:gt(0)").remove();
        if(this.value!=='0'){
            $.post(url, { pid: this.value }, function(data){
                if(data.status===0){
                    alert(data.info);
                }else{
                    $.each(data.list,function(i,o){
                        $zone.append('<option value="'+o.id+'">'+o.region+'</option>');
                    });
                }
            });
        }
    });
}
/* 
 * 订单模块
 */
$.order = {};
$.order.init = function(){
    $('#js-address-select').bind('change',function(){
        var addr_id = this.value;
        var url = $(this).attr("data-url");
        $.each(address_list,function(i,o){
            if(o.id === addr_id){
                $('.js-truename').val(o.truename);
                $('.js-address').val(o.address);
                $('.js-zipcode').val(o.zipcode);
                $('.js-phone').val(o.phone);
                $('.js-mobile').val(o.mobile);
                $.post(url,{province_id:o.province_id,city_id:o.city_id,zone_id:o.zone_id},function(data){
                    if(data.status===1){
                        $('#js-region-section').html(data.html);
                    }else{
                        alert(data.info);
                    }
                },'json');
            }
        });
    });
    $("#js-add-item").bind("click", function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popProduct';
        diag.URL = url;
        diag.Title = '选择产品';
        diag.Width = $(window).width();
        diag.Height = $(window).height() - 50;
        diag.OKEvent = function() {
            $con = $('#js-order-items tbody');
            diag.innerWin.$('#js-products :checked').each(function() {
                if($con.find('tr[data-skuid="'+this.value+'"]').length > 0){
                    return;
                }
                var html = '<tr data-skuid="'+this.value+'">\
                                <td><img src="'+$(this).attr('data-img')+'" height="40"></td>\
                                <td>'+this.value+'</td>\
                                <td>' + $(this).attr('data-sno') +'</td>\
                                <td>' + $(this).attr('data-name') +'</td>\
                                <td>' + $(this).attr('data-price') +'</td>\
                                <td><input type="text" name="items['+this.value+']" value="1" class="form-control-sm t-xxs js-item" data-price="' + $(this).attr('data-price') +'" ></td>\
                                <td>' + $(this).attr('data-price') +'</td>\
                                <td><a href="javascript:;" class="js-remove-item"><i class="glyphicon glyphicon-minus-sign red"></i></a></td>\
                            </tr>';
                $con.append(html);
            });
            $.order.calculate();
            diag.close();
        };
        diag.show();
    });
    $('#js-order-items').on('change', '.js-item', function(){
        $.order.calculate();
    }).on('click','.js-remove-item',function(){
        $o = $(this).parent().parent();
        $o.remove();
        $.order.calculate();
    });
    $('#js-costShip, #js-discount').bind('change', function(){
        $.order.calculate();
    });
    //发票
    $('#js-order-invoice').on('click', 'thead :radio', function(){
        if(this.value==='1'){
            $('#js-order-invoice tbody').removeClass('hidden');
        }else{
            $('#js-order-invoice tbody').addClass('hidden');
        }
    });
    //取消订单，订单发货，交易完成
    $('#js-cancelOrder, #js-shipOrder,#js-completeOrder').bind('click', function(){
        if(confirm("您确定要继续吗？")){
            var url = $(this).attr('data-url');
            $.getJSON(url, function(data){
                if(data.status===1){
                    reload();
                }else{
                    alert(data.info);
                }
            });
        }
    });
    //修改地址
    $('#js-editAddr,#js-editExpress,#js-editInvoice').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '修改信息';
        diag.Width = 600;
        diag.Height = 400;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    });
    //支付订单
    $('#js-payOrder').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '支付订单';
        diag.Width = 600;
        diag.Height = $(window).height() - 200;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    });
    //修改订单产品
    $('#js-editItems').bind('click',function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '编辑订单产品';
        diag.Width = $(window).width();
        diag.Height = $(window).height();
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    });
    //修改运费，折扣
    $('.js-modifyCost').bind('click', function(){
        var url = $(this).attr('data-url');
        var $input = $(this).siblings(":text");
        var cost = $input.val();
        $.post(url, {cost:cost}, function(data){
            if(data.status===1){
                $input.val(data.cost);
                $('#js-totalAmount').val(data.total_amount);
                $('.top-right').notify({
                    message : {text:'修改成功。'}
                }).show();
            }else{
                alert(data.info);
            }
        },'json');
    });
    //退货/退款
    $('#js-refundOrder').bind('click', function(){
        var url = $(this).attr('data-url');
        var diag = new Dialog();
        diag.ID = 'popDialog';
        diag.URL = url;
        diag.Title = '退货/退款';
        diag.Width = $(window).width();
        diag.Height = $(window).height() - 200;
        diag.OKEvent = function() {
            diag.innerWin.$('#j-fmData').trigger('submit');
        };
        diag.show();
    });
    //管理员备注
    $('#js-editRemark').bind('change', function(){
        var url = $(this).attr('data-url');
        var remark = $(this).val();
        $.post(url, {remark:remark},function(data){
            if(data.status===1){
                $('.top-right').notify({
                    message : {text:'管理员备注已更新。'}
                }).show();
            }else{
                alert(data.info);
            }
        },'json');
    });
    $.region.init();
}
$.order.calculate = function(){
    var cost_item = 0, total_amount = 0;
    $('#js-order-items .js-item').each(function(){
        var num = intval(this.value);
        num = num === 0 ? 1 : num;
        this.value = num;
        var price = $(this).attr('data-price') * 100;
        cost_item += price * num;
    });
    var cost_ship = parseFloat($('#js-costShip').val()) * 100;
    var discount = parseFloat($('#js-discount').val()) * 100;
    total_amount = ((cost_item + cost_ship - discount) / 100 ).toFixed(2);
    cost_item = (cost_item / 100).toFixed(2);
    
    $("#js-costItem").val(cost_item);
    $("#js-totalAmount").val(total_amount);
}
/* 
 * 模块
 */
