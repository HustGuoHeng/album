var myApp = new Framework7({
    pushState: true,
    swipePanel: 'right'
    // ... other parameters
});
window.parentId = 0;
var $$ = Framework7.$;

var mainView = myApp.addView('.view-main', {
    dynamicNavbar: true
});

$$('form.image-ajax-submit').on('submitted', function (e) {
    // var xhr = e.detail.xhr; // actual XHR object
    var data = JSON.parse(e.detail.data);
    if (data && data.status && data.status == 1) {
        myApp.alert('上传成功', '图片', function () {
            $$("#img_preview").attr('src', ' ');
            $$('#image').val('');
            $$("#image-name").val('');
            myApp.closeModal('.popup-image-upload');
        });
    } else {
        myApp.alert('上传失败'+data.msg, '图片', function () {
            console.log(data.msg);
        });
    }
});

$$('form.dir-ajax-submit').on('submitted', function (e) {
    var data = JSON.parse(e.detail.data);
    if (data && data.status && data.status == 1) {
        myApp.alert('添加成功', '目录', function () {
            myApp.closeModal('.popup-dir-upload');
        })
    } else {
        myApp.alert('添加失败', '目录', function () {
            console.log(data);
        })
    }
});


$$('.popup-image-upload .submit').on('click', function (e) {
    var form = $$('form.image-ajax-submit');
    rewriteFormAction(form);
    form.trigger('submit');
});
$$('.popup-dir-upload .submit').on('click', function (e) {
    var form = $$('form.dir-ajax-submit');
    rewriteFormAction(form);
    form.trigger('submit');
});

function rewriteFormAction(form) {
    var action = form.attr('action');
    action = action.split('?');
    action = action['0'];
    form.attr('action', action + "?" + "parentId=" + window.parentId);
}

$$(document).on('pageInit', function (e) {
    var page = e.detail.page;
    if (page.name === 'file-list') {
        var match = page.url.match(/^.*\/(\d{1,})\?*.*$/);
        window.parentId = match['1'];
    }
});
myApp.onPageBack('file-list', function (page) {
    var activePage = page.view.activePage;
    console.log(activePage);
    if (activePage.fromPage.name == 'file-list') {
        var match = activePage.fromPage.url.match(/^.*\/(\d{1,})\?*.*$/);
        window.parentId = match['1'];
    } else if (activePage.fromPage.name == 'index') {
        window.parentId = 0;
    }
});


function previewFile() {
    var preview = document.getElementById('img_preview');
    var file = document.getElementById('image').files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}


//图片相册
$$(document).on('click', '.album-pictures .album-picture-inner', function () {
    var photos = [];
    var index = 0;
    var _this = $$(this).find('.album-picture').eq(0);
    $$(this).parents('.album-pictures').find('.album-picture').each(function (a, b) {
        if ($$(this).data('id') == _this.data('id')) {
            index = a;
        }
        photos.push({
            'url': $$(this).data('image'),
            'caption': $$(this).html()
        });
    });
    var myPhotoBrowser = myApp.photoBrowser({
        zoom: 400,
        photos: photos,
        initialSlide: index,
        theme: 'dark',
        lazyLoading: true,
        lazyLoadingInPrevNext: false,
        lazyLoadingOnTransitionStart: true,
        ofText: '/'
    });
    myPhotoBrowser.open(); // 打开图片浏览器
});
