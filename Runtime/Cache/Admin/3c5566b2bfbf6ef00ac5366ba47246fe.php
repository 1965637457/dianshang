<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administrator Login Panel</title>
        <link href="/hamanaka3/Public/Admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body{background:url(/hamanaka3/Public/Admin/images/login_bg.gif) repeat-x #fff;padding:100px 0 50px;}
            .form-login{width:300px;margin:0 auto;}
            .input-verify{width:50%;}
            .img-verify{cursor: pointer;}
        </style>
        <!--[if lt IE 9]>
          <script src="/hamanaka3/Public/Admin/bootstrap/js/html5shiv.min.js"></script>
          <script src="/hamanaka3/Public/Admin/bootstrap/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <h1 class="text-center"><img src="/hamanaka3/Public/Admin/images/login_logo.png" ></h1>
            <form class="form-login" action="<?php echo U('Public/dologin');?>" method="post" id="js-fmLogin">
                <div class="form-group">
                    <input type="text" name="account" class="form-control" id="js-account" placeholder="Account" >
                </div>
                <div class="form-group">
                    <input type="password" name="pwd" class="form-control" id="js-pwd" placeholder="Password" >
                </div>
                <div class="form-group">
                    <input type="text" name="verify" class="form-control input-verify pull-left" id="js-verify" placeholder="Verify Code" >
                    <img src="<?php echo U('Public/verify');?>" class="img-verify" id="js-imgVerify" title="click to fresh" />
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
            </form>
        </div>
        <script src="/hamanaka3/Public/Admin/js/jquery.min.js"></script>
        <script src="/hamanaka3/Public/Admin/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(function() {
                var verify_url = $('#js-imgVerify').attr('src');
                $(document).on('click', '#js-imgVerify', function(){
                    this.src = verify_url + '?' + Math.random();
                });
                $('#js-fmLogin').bind('submit', function() {
                    if ($('#js-account').val() === '') {
                        alert('Account is missing!');
                        return false;
                    }
                    if ($('#js-pwd').val() === '') {
                        alert('Password is missing!');
                        return false;
                    }
                    if ($('#js-verify').val().length !== 4) {
                        alert('Verify code is missing!');
                        return false;
                    }
                    $.post(this.action, $(this).serialize(), function(data) {
                        if (data.status === 0) {
                            $('#js-imgVerify').trigger('click');
                            alert(data.info);
                        } else {
                            window.location.reload();
                        }
                    }, 'json');
                    return false;
                });
            });
        </script>
    </body>
</html>