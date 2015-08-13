$(function() {
    initSelectAll();
    initListHandle();
    initBack();
    initSetStatus();
    initDel();
    initJumpPage();
    initFresh();
    /*鼠标悬停下拉列表*/
    $('.js-dropdown-hover').hover(function() {
        $(this).toggleClass('open');
    });
    /*列表上修改排序*/
    $('.js-sort').bind('change', function() {
        var id = $(this).attr('data-id');
        var val = this.value;
        $.post(this.form.action, {handle_type: 'sort', id: id, val: val}, function(data) {
            if (data.status === 0) {
                alert(data.info);
            }
        }, 'json');
    });
    /*还原表单原有的值*/
    $(':text').each(function() {
        this.value = this.defaultValue;
    });
    $('.form-control-sm').focus(function(){
        $(this).parent().removeClass('has-error has-warning has-success');
    });
    /*二次确认是否继续*/
    $('.js-confirm').bind('click', function() {
        return confirm('您确定要继续吗？');
    });
    /*选项卡禁用功能*/
    $('.nav-tabs .disabled a').click(function(e) {
        e.preventDefault();
        return false;
    });
    /*移动排序,需三层结构*/
    $(document).on('click','.js-move-up',function(){
        var $o = $(this).parent().parent();
        $prev = $o.prev();
        $o.insertBefore($prev);
    }).on('click','.js-move-down',function(){
        var $o = $(this).parent().parent();
        $next = $o.next();
        $o.insertAfter($next);
    });
});
