<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>跳转提示</title>
        <link href="/hamanaka3/Public/Admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
            .system-message{width:500px;padding:20px;border:2px solid red;margin: 20px auto 0;text-align:center;}
            .glyphicon{color:red;}
        </style>
    </head>
    <body>
        <div class="system-message">
            <h3><span class="glyphicon glyphicon-remove"></span> <?php echo ($error); ?></h3>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
            </p>
        </div>
        <script type="text/javascript">
            (function() {
                var wait = document.getElementById('wait'), href = document.getElementById('href').href;
                var interval = setInterval(function() {
                    var time = --wait.innerHTML;
                    if (time <= 0) {
                        location.href = href;
                        clearInterval(interval);
                    }
                }, 1000);
            })();
        </script>
    </body>
</html>