<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo (C("META_TITLE")); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta content="<?php echo (C("META_KEYWORD")); ?>" name="keywords"/>
        <meta content="<?php echo (C("META_DESCRIPTION")); ?>" name="description"/>
        <link href="/ham/Public/Home/style.css" type="text/css" rel="stylesheet"/>
        <link href="/ham/Public/Home/font.css" type="text/css" rel="stylesheet"/>
        <script type="text/javascript" src="/ham/Public/Home/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="/ham/Public/Home/js/common.js"></script>
        <!--[if lte IE 6]>
        <script src="/ham/Public/Home/js/png.min.js" type="text/javascript"></script>
            <script type="text/javascript">
                DD_belatedPNG.fix('h1, h2, h3, div, ul, img, li, input, a, span');
            </script>
        <![endif]-->
        <!--[if IE 8]>
        <link href="/ham/Public/Home/ie8.css" type="text/css" rel="stylesheet"/>
        <![endif]-->
        <!--[if IE 7]>
        <link href="/ham/Public/Home/ie7.css" type="text/css" rel="stylesheet"/>
        <![endif]-->
        <!--[if IE 6]>
        <link href="/ham/Public/Home/ie6.css" type="text/css" rel="stylesheet"/>
        <![endif]-->
    </head>
    <body>
<div class="top">
    <div class="width clear"><div class="top_link"><a href="<?php echo U('/Sitemap');?>">Sitemap</a><span>|</span><a href="<?php echo U('/Contact');?>">Contact us</a></div></div>
</div>
<div id="j-space" class="hidden"></div>
<div id="j-header" class="header">
    <div class="width clear">
        <div class="logo">
            <h1><a href="<?php echo U('/');?>"><img src="/ham/Public/Home/images/logo.jpg"></a></h1>
        </div>
        <div class="menu_rightside">
            <div class="search top_search">
                <form action="<?php echo U('/Search');?>" method='get'>
                    <input type="text" class="text" name="kw" placeholder="Search Kehong..." style="width:260px;" />
                    <button type="submit" class="submit"></button>
                </form>
            </div>
            <ul class="menu">
                <li><a href="<?php echo U('/');?>" <?php if((CONTROLLER_NAME) == "Index"): ?>class="on"<?php endif; ?>>Home</a></li>
                <li class="j-sup">
                    <a href="<?php echo U('/about');?>" <?php if((CONTROLLER_NAME) == "About"): ?>class="on"<?php endif; ?>>About us</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["About"])): $i = 0; $__LIST__ = $nav_list["About"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('About/detail',array('id'=>$v['id'])); else: echo U('About/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/product');?>" <?php if((CONTROLLER_NAME) == "Product"): ?>class="on"<?php endif; ?>>Products</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["Product"])): $i = 0; $__LIST__ = $nav_list["Product"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Product/category',array('id'=>$v['id'])); else: echo U('Product/category/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["cate_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/news');?>" <?php if((CONTROLLER_NAME) == "News"): ?>class="on"<?php endif; ?>>News</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["News"])): $i = 0; $__LIST__ = $nav_list["News"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('News/category',array('id'=>$v['id'])); else: echo U('News/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["cate_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/service');?>" <?php if((CONTROLLER_NAME) == "Service"): ?>class="on"<?php endif; ?>>Services</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["Service"])): $i = 0; $__LIST__ = $nav_list["Service"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Service/detail',array('id'=>$v['id'])); else: echo U('Service/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/resource');?>" <?php if((CONTROLLER_NAME) == "Resource"): ?>class="on"<?php endif; ?>>Resources</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["Resource"])): $i = 0; $__LIST__ = $nav_list["Resource"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Resource/detail',array('id'=>$v['id'])); else: echo U('Resource/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/quality');?>" <?php if((CONTROLLER_NAME) == "Quality"): ?>class="on"<?php endif; ?>>Quality</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["Quality"])): $i = 0; $__LIST__ = $nav_list["Quality"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Quality/detail',array('id'=>$v['id'])); else: echo U('Quality/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <li class="j-sup">
                    <a href="<?php echo U('/technology');?>" <?php if((CONTROLLER_NAME) == "Technology"): ?>class="on"<?php endif; ?>>Technology</a>
                    <ul class="menu_dropdown j-sub hidden">
                        <?php if(is_array($nav_list["Technology"])): $i = 0; $__LIST__ = $nav_list["Technology"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Technology/detail',array('id'=>$v['id'])); else: echo U('Technology/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="content width">
    <div class="inner_page clear">
        <div class="error">
            <h2>404 Page not found!</h2>
            <p>Sorry, but the page you are looking for has not been found. you might try to check the url or refresh the page.</p>
            <a href="<?php echo U('/');?>">Return to the Home page</a>
        </div>
    </div>
</div>
<div class="footer">
    <div class="width clear">
        <div class="footer_products clear">
            <h3>Some parts of our main  products are Rare Earths</h3>
            <ul>
                <?php if(is_array($bottom_pros)): $i = 0; $__LIST__ = $bottom_pros;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('detail',array('id'=>$v['id'])); else: echo U('Product/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <a href="<?php echo U('Product');?>" class="btn_products">View All Products +</a>
        </div>
        <div class="footer_contact">
            <h3>Contact Us</h3>
            <p><strong>Tel:</strong> <span><?php echo (C("CONTACT_PHONE")); ?></span></p> 
            <p><strong>Fax:</strong> <?php echo (C("CONTACT_FAX")); ?></p>
            <p><strong>E-mail:</strong> <?php echo (C("CONTACT_EMAIL")); ?></p>
            <img src="/ham/Public/Home/images/maps.png">
        </div>
        <div class="footer_social">
            <h3>Stay Connected</h3>
            <a href="#" target="_blank" class="facebook social">facebook</a>
            <a href="#" target="_blank" class="twitter social">twitter</a>
            <a href="#" target="_blank" class="google social">google</a>
            <a href="#" target="_blank" class="pinterest social">pinterest</a>
            <a href="#" target="_blank" class="linkedin social">linkedin</a>
            <div class="mail_send">
                <p>Get product updates and other news.We will never sell your email address. Read our <a href="<?php echo U('Info/privacy-policy');?>">Privacy policy.</a> </p>
                <form action="<?php echo U('Index/subscribe');?>" method="post" id="j-fmSubscribe">
                    <input type="text" name="email" class="require email" placeholder="Enter your email address">
                    <button type="submit" value="submit">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="copyright clear">
            <p><?php echo (C("META_COPYRIGHT")); ?></p>
            <div class="copyright_link"><a href="<?php echo U('Info/privacy-policy');?>">Privacy Policy</a><span>|</span><a href="<?php echo U('Info/terms-conditions');?>">Terms & Conditions</a><span>|</span><a href="<?php echo U('/Sitemap');?>">Site Map</a></div>
        </div>
    </div>
</div>
</body>
</html>