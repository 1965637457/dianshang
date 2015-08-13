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
<div id="banner">
    <ul class="clear">
        <?php if(is_array($index_banners)): $i = 0; $__LIST__ = $index_banners;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li <?php if(($i) == "1"): ?>class="on"<?php endif; ?>></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <div id="banner_list">
        <?php if(is_array($index_banners)): $i = 0; $__LIST__ = $index_banners;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo ($v["link"]); ?>"><img src="/ham/Uploads/<?php echo ($v["image"]); ?>" alt="<?php echo ($v["title"]); ?>" title="<?php echo ($v["title"]); ?>"/></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<div class="content width">
    <div class="index_product clear">
        <?php if(is_array($nav_list["Product"])): $i = 0; $__LIST__ = $nav_list["Product"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="<?php if(($v["id"]) == "1"): ?>product_oxides<?php endif; if(($v["id"]) == "2"): ?>product_metals<?php endif; if(($v["id"]) == "3"): ?>product_materials<?php endif; ?>">
            <h2><a href="<?php if(empty($v["rewriteuri"])): echo U('Product/category',array('id'=>$v['id'])); else: echo U('Product/category/'.$v['rewriteuri']); endif; ?>"><?php echo ($v["cate_name"]); ?></a></h2>
            <p><?php echo (msubstr($v["short_desc"],0,80)); ?></p>
            <?php switch($v["id"]): case "1": ?><img src="/ham/Public/Home/images/icon_oxides.png"><?php break;?>
            <?php case "2": ?><img src="/ham/Public/Home/images/icon_metals.png"><?php break; endswitch;?>
            <ul class="product_navbar clear">
                <?php if(is_array($v["_list"])): $i = 0; $__LIST__ = $v["_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($vv["rewriteuri"])): echo U('Product/detail',array('id'=>$vv['id'])); else: echo U('Product/'.$vv['rewriteuri']); endif; ?>"><?php echo ($vv["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <a href="<?php if(empty($v["rewriteuri"])): echo U('Product/category',array('id'=>$v['id'])); else: echo U('Product/category/'.$v['rewriteuri']); endif; ?>" class="view_all">View All</a>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="index_about">
        <h3><a href="<?php echo U('/About');?>">About<br />Kehong Rare Earth</a></h3>
        <p>KEHONG RARE EARTH specializes in Rare Earth industry for decades and now is a leading manufacturer and supplier of various Rare Earth Oxides, Metals & Alloys of China. </p>
        <p></p>
    </div>
    <div class="index_news_services clear">
        <?php if(is_array($news)): $i = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="index_news_box">
            <a href="<?php echo U('News/detail',array('id'=>$v['id']));?>"><img src="/ham/Uploads/<?php echo ($v["image"]); ?>" onerror="this.src='/ham/Public/Home/images/news_default.jpg'" alt="<?php echo ($v["title"]); ?>" /></a>
            <span><?php echo (date('d M Y',$v["publish_time"])); ?></span>
            <h3><a href="<?php echo U('News/detail',array('id'=>$v['id']));?>"><?php echo ($v["title"]); ?></a></h3>
            <p><?php echo (msubstr(strip_html_tags($v["content"]),0,240)); ?></p>
            <a href="<?php echo U('News/detail',array('id'=>$v['id']));?>" class="read_more">Read More +</a>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="index_services">
            <h3>Services</h3>
            <p>Customers satisfaction is guaranteed!in Rare Earth.</p>
            <ul>
                <?php if(is_array($nav_list["Service"])): $i = 0; $__LIST__ = $nav_list["Service"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php if(empty($v["rewriteuri"])): echo U('Service/detail',array('id'=>$v['id'])); else: echo U('Service/'.$v['rewriteuri']); endif; ?>">· <?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <img src="/ham/Public/Home/images/services_1.jpg">
        </div>
    </div>
</div>






<script>
    //banner切换
    var t = n = 0, count;
    $(function() {
        count = $("#banner_list a").length;
        $("#banner_list a:not(:first-child)").hide();
        $("#banner_info").html($("#banner_list a:first-child").find("img").attr('alt'));
        $("#banner_info").click(function() {
            window.open($("#banner_list a:first-child").attr('href'), "_blank")
        });
        $("#banner li").click(function() {
            var i = $(this).index();
            n = i;
            if (i >= count)
                return;
            $("#banner_info").html($("#banner_list a").eq(i).find("img").attr('alt'));
            $("#banner_info").unbind().click(function() {
                window.open($("#banner_list a").eq(i).attr('href'), "_blank")
            })
            $("#banner_list a").filter(":visible").fadeOut(600).parent().children().eq(i).fadeIn(1100);
            document.getElementById("banner").style.background = "";
            $(this).toggleClass("on");
            $(this).siblings().removeAttr("class");
        });
        t = setInterval("showAuto()", 4000);
        $("#banner").hover(function() {
            clearInterval(t)
        }, function() {
            t = setInterval("showAuto()", 4000);
        });
    })
    function showAuto()
    {
        n = n >= (count - 1) ? 0 : ++n;
        $("#banner li").eq(n).trigger('click');
    }
</script>
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