var myApp = new Framework7({
    pushState: true,
    swipePanel: 'right',
    // ... other parameters
});

var $$ = Framework7.$;

var mainView = myApp.addView('.view-main', {
    // Because we want to use dynamic navbar, we need to enable it for this view:
    dynamicNavbar: true
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
// })
