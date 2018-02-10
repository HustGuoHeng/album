var myApp = new Framework7({
    pushState: true,
    swipePanel: 'right',
    // ... other parameters
});

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
        myApp.alert('上传失败', '图片', function () {
            console.log(data);
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
    $$('form.image-ajax-submit').trigger('submit');
});
$$('.popup-dir-upload .submit').on('click', function (e) {
   $$('form.dir-ajax-submit').trigger('submit');
});
// Now we need to run the code that will be executed only for About page.
// For this case we need to add event listener for "pageInit" event

// Option 1. Using one 'pageInit' event handler for all pages (recommended way):
// $$(document).on('pageInit', function (e) {
  // Get page data from event data
  // var page = e.detail.page;
  
  // if (page.name === 'about') {
    // Following code will be executed for page with data-page attribute equal to "about"
    // myApp.alert('Here comes About page');
  // }
// });

function previewFile() {
    var preview = document.getElementById('img_preview');
    var file    = document.getElementById('image').files[0];
    var reader  = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}

