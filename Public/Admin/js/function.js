/*鼠标经过添加hover类*/
function initAddHover(){
    $('.j-hover').hover(function(){
        $(this).addClass('hover');
    },function(){
        $(this).removeClass('hover');
    });
}
/*鼠标经过展示下级，需.j-sup和.j-sub和.hidden*/
function initShowSub(){
    $('.j-sup').hover(function(){
        $('.j-sub',this).removeClass('hidden');
    },function(){
        $('.j-sub',this).addClass('hidden');
    });
}
/*全选*/
function initSelectAll(){
    $('.j-check-all').bind('click',function(){
        var checked = this.checked;
        $t = $(this).parent().parent().parent().parent();
        $.each($(':checkbox',$t), function(){
            this.checked = checked;
        });
    });
}
function initListHandle(){
    $('.js-handle').bind('click',function(){
        var handle_type = $(this).attr('data-type');
        if(handle_type===''){
            return false;
        }
        var $form = $('#js-form-handle');
        if($(':checked',$form).length<1){
            alert('没有选择任何项目!至少选一个复选框来执行此操作!');
            return false;
        }
        if($(this).hasClass('js-need-confirm') && !confirm("您确定要继续吗?")){
            return false;
        }
        $('.js-handle-type').val(handle_type);
        $form.submit();
    });
}
/*页码跳转*/
function initJumpPage(){
    $('.j-jumpPage').bind('click',function(){
        var page = $(this).siblings(':text').val();
        var href = window.location.href;
        if(href.indexOf('/p/')===-1){
            window.location.href += '/p/'+page;
        }else{
            window.location.href = href.replace(/\/p\/\d+/,'/p/'+page);
        }
    });
}
/*返回*/
function initBack(){
    $('.j-back').bind('click',function(){
        back();return false;
    });
}
/*邮箱验证*/
function isEmail(str){
    var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    return reg.test(str);
}
/*返回上一页*/
function back(){
    window.history.back();
}
/*刷新*/
function refresh(){
    window.location.reload();
}
/*重新载入*/
function reload(){
    window.location.assign(window.location.href);
}
function initFresh(){
    $('.j-refresh').bind('click',function(){
        refresh();
    });
}
/*设置状态*/
function initSetStatus(){
    $('.j-status').bind('click',function(){
        var $o = $(this);
        var url = $(this).attr('data-url');
        $.getJSON(url,function(data){
            if(data.status===0){
                refresh();
            }else{
                $o.text(data.data).attr('data-url',data.url).removeClass('status-1 status-0').addClass(data.className);
            }
        });
    });
}
/*删除数据*/
function initDel(){
    $('.j-del').each(function(){
        var url = this.href;
        if(url.substr(-5) !== '#none'){
            this.href = '#none';
            $(this).attr('data-url',url);
        }
        $(this).unbind('click').bind('click',function(){
            return removeData(this);
        });
    });
}
function removeData(o){
    if(confirm('确定删除？')){
        if($(o).attr('data-url')){
            $.getJSON($(o).attr('data-url'),function(data){
                if(data.status===0){
                    alert(data.info);
                }else{
                    if(data.info==='refresh'){
                        refresh();
                    }else{
                        $(o).parent().parent().fadeOut(500,function(){
                            $(this).remove();
                        });
                    }
                }
            });
        }else{
            $(o).parent().parent().fadeOut(500,function(){
                $(this).remove();
            });
        }
    }
    return false;
}
/*切换标签*/
function initTabs(){
    $('.j-tabs').each(function(){
        var tab = $(this).attr('data-tab');
        $('li',this).each(function(m,o){
            var $li = $(o);
            $('a',this).bind('click',function(){
                $li.addClass('current').siblings().removeClass('current');
                $('.'+tab).eq(m).removeClass('hidden').siblings('.'+tab).addClass('hidden');
                return false;
            });
        });
    });
}
function checkRequire(form){
    var flag = true;
    $(':text.require', form).each(function(){
        var val = this.value;
        if($.trim(val)==='' || ($(this).hasClass('isNumber') && !$.isNumeric(val))){
            flag = false;
            setError(this);
        }
    });
//    $('select.require', form).each(function(){
//
//    });
    return flag;
}
function setError(el){
    $(el).parent().addClass('has-error');
}
function intval(str){
    var i = parseInt(str);
    if(isNaN(i)){
        i = 0;
    }
    return i;
}

