<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>elFinder 2.0</title>
        <!-- jQuery and jQuery UI (REQUIRED) -->
        <script type="text/javascript" src="/hamanaka3/Public/Admin/js/elfinder/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/hamanaka3/Public/Admin/js/elfinder/js/jquery/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="/hamanaka3/Public/Admin/js/elfinder/js/jquery/jquery-ui.min.css">

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" media="screen" href="/hamanaka3/Public/Admin/js/elfinder/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="/hamanaka3/Public/Admin/js/elfinder/css/theme.css">

        <!-- elFinder JS (REQUIRED) -->
        <script type="text/javascript" src="/hamanaka3/Public/Admin/js/elfinder/js/elfinder.min.js"></script>

        <!-- elFinder translation (OPTIONAL) -->
        <script type="text/javascript" src="/hamanaka3/Public/Admin/js/elfinder/js/i18n/elfinder.zh_CN.js"></script>

        <!-- elFinder initialization (REQUIRED) -->
        <script>
            $().ready(function() {
                var elf = $('#elfinder').elfinder({
                    url: "<?php echo U('Editor/connectElfinder');?>",  // connector URL (REQUIRED)
                    getFileCallback : function(file) {
                        window.opener.$.spec.setFile(file);
                        window.close();
                    },
                    lang: 'zh_CN',
                    resizable: false
                }).elfinder('instance');
            });
        </script>
    </head>
    <body>

        <!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>

    </body>
</html>