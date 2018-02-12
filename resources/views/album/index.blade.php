<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
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
<!-- About Popup -->
<div class="popup popup-image-upload view">
    <!-- Top Navbar-->
    <div class="navbar">
        <div class="navbar-inner">
            <!-- We need cool sliding animation on title element, so we have additional "sliding" class -->

            <div class="left">
                <a href="#" class="close-popup link">
                    <i class="icon icon-back"></i>
                    <span>Back</span>
                </a>
            </div>
            <div class="center">图片上传</div>
            <div class="right">
                <a href="#" class="submit link">
                    <span>&nbsp;提交&nbsp;</span>
                </a>
            </div>
        </div>
    </div>
    <div class="list-block">
        <div class="content-block-title"></div>
        <div class="list-block">
            <form action="{{ URL::asset('upload/image') }}" class="image-ajax-submit ajax-submit upload-ajax-submit"
                  method="post"
                  enctype="multipart/form-data">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" id='image-name' name="name" placeholder="图片名称">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <label for="image">
                                        <i class="icon icon-camera"></i>
                                        <span>选择图片</span>
                                    </label>
                                    <input id="image" onchange="previewFile()" name='image' style="display: none"
                                           type="file" accept="image/*"/>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <img id="img_preview" src="" width="200" style="margin: auto">

            </form>
        </div>
    </div>

</div>

<div class="popup popup-dir-upload view">
    <!-- Top Navbar-->
    <div class="navbar">
        <div class="navbar-inner">
            <!-- We need cool sliding animation on title element, so we have additional "sliding" class -->

            <div class="left">
                <a href="#" class="close-popup link">
                    <i class="icon icon-back"></i>
                    <span>Back</span>
                </a>
            </div>
            <div class="center">添加目录</div>
            <div class="right">
                <a href="#" class="submit link">
                    <span>&nbsp;提交&nbsp;</span>
                </a>
            </div>
        </div>
    </div>
    <div class="list-block">
        <div class="content-block-title"></div>
        <div class="list-block">
            <form action="{{ URL::asset('upload/dir') }}" class="dir-ajax-submit ajax-submit upload-ajax-submit"
                  method="post">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" id='dir-name' name="name" placeholder="目录名称">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

<div class="views">

    <!-- Your main view, should have "view-main" class -->
    <div class="view view-main">

        <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <!-- We need cool sliding animation on title element, so we have additional "sliding" class -->
                <div class="center sliding">{{$name}}</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a>
                </div>
            </div>
        </div>
        <div class="speed-dial">
            <!-- FAB inside will open Speed Dial actions -->
            <a href="#" class="floating-button color-pink">
                <!-- First icon is visible when Speed Dial actions are closed -->
                <i class="icon icon-plus"></i>
                <!-- Second icon is visible when Speed Dial actions are opened -->
                <i class="icon icon-close"></i>
            </a>
            <!-- Speed Dial Actions -->
            <div class="speed-dial-buttons">
                <a href="#" data-popup=".popup-image-upload" class="open-popup">
                    图片
                </a>

                <a href="#" data-popup=".popup-dir-upload" class="open-popup">
                    目录
                </a>
            </div>
        </div>
        <!-- Pages container, because we use fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
            <!-- Page, "data-page" contains page name -->
            <div data-page="index" class="page">
                <!-- Scrollable page content -->
                <!-- Floatin Action Button -->
                <div class="page-content">
                    <div class="list-block">
                        @include('album/fileList', ['data' => $data])
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