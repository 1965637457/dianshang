var siteUrl = '/admin.php';
$(function(){
    $('#verifyImg').bind('click',function(){
        this.src = siteUrl + '/Public/verify/' + Math.random();
    });
    $('#loginForm').bind('submit',function(){
        if($('#account').val()===''){alert('请输入登录用户名');return false;}
        if($('#pwd').val()===''){alert('请输入登录密码');return false;}
        if($('#verify').val().length!==4){alert('请输入验证码');return false;}
        $.post(this.action,$(this).serialize(),function(data){
            if(data.status===0){$('#verifyImg').trigger('click');alert(data.info);}else{window.location.reload();}
        },'json');
        return false;
    });
});