<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- Your app title -->
    <title>工作区</title>
    <!-- Path to Framework7 Library CSS, iOS Theme -->
    <link rel="stylesheet" href="{{ URL::asset('css/framework7.ios.min.css') }}">
    <!-- It should be included After main Framework7 styles -->
    <link rel="stylesheet" href="{{ URL::asset('css/framework7.ios.colors.min.css') }}">
    <!-- Path to Framework7 color related styles, iOS Theme -->
    <!-- <link rel="stylesheet" href="./css/framework7.ios.colors.min.css"> -->
    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="{{ URL::asset('css/my-app.css') }}">
</head>
<body class="theme-lightblue">
<div class="panel-overlay"></div>
<div class="panel panel-left panel-cover">
    <div class="content-block">
        <p>目前这里还在测试哦，有什么需要可以在公众号上回复或者发送到hustguoheng@163.com</p>
        <p><a href="#" class="close-panel">关闭</a></p>
    </div>
</div>

<div class="views">

    <!-- Your main view, should have "view-main" class -->
    <div class="view view-main">

        <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <!-- We need cool sliding animation on title element, so we have additional "sliding" class -->
                <div class="center sliding">HustGuoHeng</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
            </div>
        </div>
        <a href="#" class="floating-button color-pink"><i class="icon icon-plus"></i></a>
        <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
            <!-- Page, "data-page" contains page name -->
            <div data-page="index" class="page">
                <!-- Scrollable page content -->
                <!-- Floatin Action Button -->
                <div class="page-content">
                    <div class="list-block">
                        <ul>
                            <li class="item-content">
                                <div class="item-media"><i class="icon icon-f7"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">Item title</div>
                                    <div class="item-after little">2017/04/04</div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-media"><i class="icon icon-f7"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">Item with badgeasdasdasdasdasds</div>
                                    <div class="item-after little">2017/04/04</div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-media"><i class="icon icon-f7"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">Another item</div>
                                    <div class="item-after little">2017/04/04</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>

    </div>
</div>
<!-- Path to Framework7 Library JS-->
<script type="text/javascript" src="{{ URL::asset('js/framework7.min.js') }}"></script>

<!-- Path to your app js-->
<script type="text/javascript" src="{{ URL::asset('js/my-app.js') }}"></script>
</body>
</html>