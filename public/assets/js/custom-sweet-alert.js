$(document).ready(function (){
    var alertDiv = $(".sweet-alert");
    var alertOverlay = $(".sweet-overlay");
    var accountingButton = $("#accountingBtn");
    var popupMenuOpened = false;
    var accountingOffsetTop = accountingButton.offset().top;

    accountingButton.hover(function () { alertCheck(); }, function (){ var x = setInterval(function (){ if(!(alertDiv.is(":hover"))){ alertCheck(); clearInterval(x);} }, 100) });
    //alertOverlay.on("click", function (){ alertCheck(); }); -- hide alert when overlay clicked

    function alertCheck(){
        if(popupMenuOpened){
            //$(document.body).removeClass("stop-scrolling"); -- to stop scrolling in main
            accountingButton.removeClass("active");
            alertDiv.removeClass("showSweetAlert visible");
            alertDiv.addClass("hideSweetAlert");
            alertDiv.css("display", "none");
            alertDiv.css("opacity", "-0.02");
            accountingButton.parent().removeClass("is-expanded");
            //alertOverlay.css("opacity", "-0.04"); -- to show an overlay on behind
            //alertOverlay.css("display", "none"); -- to show an overlay on behind
            popupMenuOpened = false;
        }else{
            //$(document.body).addClass("stop-scrolling"); -- to stop scrolling in main
            accountingButton.addClass("active");
            alertDiv.removeClass("hideSweetAlert");
            alertDiv.addClass("showSweetAlert visible");
            alertDiv.css("display", "block");
            alertDiv.css("opacity", "1");
            alertDiv.css("top", ((accountingOffsetTop - $(".app-sidebar").scrollTop() - (alertDiv.height() / 2)).toString()+"px"));
            accountingButton.parent().addClass("is-expanded");
            //alertOverlay.css("opacity", "1.06"); -- to show an overlay on behind
            //alertOverlay.css("display", "block"); -- to show an overlay on behind
            popupMenuOpened = true;
        }
    }
});
