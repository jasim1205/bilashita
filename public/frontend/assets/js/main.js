$("#showSearch,#showMobileSearch").click(function (event) {
    event.stopPropagation()
    $(".search-box").css("display", "flex")
})
$(".search-box").click(function (event) {
    event.stopPropagation()
})
$("body").click(function () {
    $(".search-box").hide()
})
// $("body").append('<div class="right-fixed-icon"><div class="rth"></div><div class="scroll-top"></div></div>')

$(document).on('click',".rth",function (){
    var windowUrl=window.location.href
    if(windowUrl.indexOf('/cn')>=0){ //中文
        window.location.href='https://shop589163330.taobao.com/'
    }else {//英文
        window.location.href='https://rmsmarine.en.alibaba.com/'
    }
})

// window.onload=function (){
//     var h=$(".body").height()
//     var w=$(window).width()
//     var ratio=w/1400
//     var hh=h*ratio
//     $('.warp').height(hh+'px')
//     $(".body").css({'transform':'scale('+ratio+')'})
// }
$(document).ready(function() {
    $("body").append('<div class="right-fixed-icon"><div class="scroll-top"></div></div>');

    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop >= 100) {
            $(".right-fixed-icon").fadeIn();
        } else {
            $(".right-fixed-icon").fadeOut();
        }

        if (scrollTop >= 200) {
            $(".business-fixed-menu").css('display', 'flex');
        } else {
            $(".business-fixed-menu").css('display', 'none');
        }
    });

    $(".scroll-top").click(function () {
        $("html, body").animate({
            scrollTop: 0
        });
    });
});

// $(window).scroll(function () {
//     var scrollTop = $(window).scrollTop()
//     if (scrollTop >= 100) {
//         $(".right-fixed-icon").fadeIn()
//     } else {
//         $(".right-fixed-icon").fadeOut()
//     }

//     //business 左侧导航
//     if (scrollTop >= 200) {
//         $(".business-fixed-menu").css('display', 'flex')
//     } else {
//         $(".business-fixed-menu").css('display', 'none')
//     }

// })
// $(".scroll-top").click(function () {
//     $("html,body").animate({
//         scrollTop: 0
//     })
// });
$(".l-menu").click(function () {
    $(".header-menu .menu").addClass('open')
})
$(".header-menu .close").click(function () {
    $(".header-menu .menu").removeClass('open')
})
$(document).on('click', '.header-menu .open', function () {
    $(".header-menu .menu").removeClass('open')
})
$(".ul-box").click(function (event) {
    event.stopPropagation()
})
$(".mobile-box.language").click(function () {
    if ($(this).hasClass('open')) {
        $(this).removeClass("open")
        $(".mobile-box.down-language").hide()
    } else {
        $(this).addClass("open")
        $(".mobile-box.down-language").show()
    }
})


//地图缩放

function enlargePageFunMap(designWidth, designHeight) {
    var windowW = $(window).width()
    if (windowW < designWidth) {
        var ratio = (windowW-100) / designWidth
        var hh = designHeight * ratio
        $('.home-page5 .map').height(hh + 'px')
        $(".home-page5 .map").css(getScaleCss(ratio))
    } else {
        $('.home-page5 .map').height('auto')
        $(".home-page5 .map").css(getScaleCss(1))
    }
}

function resizeIndexMap(){
    if ($(window).width() <= 1400) {
        enlargePageFunMap(1380, 684)
    }
}
resizeIndexMap()
$(window).resize(function () {
    resizeIndexMap()
})

/*
    designWidth设计宽度
    designHeight设计高度
 */

function enlargePage(designWidth, designHeight) {
    enlargePageFun(designWidth, designHeight)
    $(".warp").fadeIn()
    $(".loading-box").fadeOut()
    $(window).resize(function () {
        console.log(designWidth, designHeight)
        enlargePageFun(designWidth, designHeight)
    })
}

function enlargePageFun(designWidth, designHeight) {
    var windowW = $('body').width()

    console.log(windowW,designWidth, designHeight)
    //if (windowW > designWidth) {
    if (windowW > 1960) {
        var ratio = windowW / designWidth
        var hh = designHeight * ratio
        $('.warp').height(hh + 'px')
        $('.warp').css({height:hh + 'px','overflow':'hidden'})
        $(".body,.fixed-dialog2").css(getScaleCss(ratio))
        $(".body").css({width:'1960px'})
        $(".banner").css({width:'1960px'})
    } else {
        $('.warp').height('auto')
        $(".body").css(getScaleCss(1))
        $(".body").css({width:'auto'})
        $(".banner").css({width:'auto'})
        if(windowW<750){
            $(".body").css({
                'transform': 'inherit',
                '-webkit-transform': 'inherit',
                '-moz-transform': 'inherit'
            })
        }
    }
}

function getScaleCss(ratio) {
    return {
        'transform': 'scale(' + ratio + ')',
        '-webkit-transform': 'scale(' + ratio + ')',
        '-moz-transform': 'scale(' + ratio + ')'
    }
}


//图片预览通用js
var openPhotoSwipe = function (items, index) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    // build items array
    // define options (if needed)
    //按钮样式设置
    var options = {
        index: index,//控制打开的一张图片
        closeEl: true,
        //bgOpacity:0.7, //背景不透明度
        // maxSpreadZoom:2, //最大缩放级别
        // getDoubleTapZoom:2, //双击手势放大图像后与之前的图像的缩放级别
        //  loop:false,// 循环
        //  pinchToClose:false,// 捏关闭画廊
        //  pinchToClose:false,// 捏关闭画廊
        //
        //  galleryUID:false,// 图库唯一ID
        history: false,// 是否记录链接历史  链接参数
        arrowKeys: true,// 键盘左右箭头导航
        captionEl: true,
        fullscreenEl: true,
        zoomEl: true,
        shareEl: true,
        counterEl: true,
        arrowEl: true,
        preloaderEl: true,
        tapToClose: true,
        tapToToggleControls: true,
        clickToCloseNonZoomable: false,  //点击关闭
    };
    console.log(options)
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
};


//select下拉
$("body").click(function () {
    $(".rms-select").removeClass('open')
})
$(".rms-select .value").click(function (ev) {
    ev.stopPropagation()
    $parents=$(this).parents(".rms-select")
    var text=$(this).text()
    $(".rms-select").removeClass('open')
    if ($parents.hasClass("open")) {
        $parents.removeClass('open')
    } else {
        $parents.addClass('open')
    }
    $parents.find('dd').each(function (){
        if($(this).hasClass('checked')){
            $(this).addClass('select')
        }else {
            $(this).removeClass('select')
        }
    })
})
$(".rms-select .select-item dd").hover(function (){
    $(this).parents('.select-item').find('dd').removeClass('select')
    $(this).addClass('select')
})
$(".rms-select .select-item dd").click(function (){
    $(".rms-select").removeClass('open')
    $(this).parents('.rms-select').find('.value').text($(this).text())
    $(this).parents('.rms-select').find('dd').removeClass('checked')
    $(this).addClass('checked')
})
$(".rms-ratio-group .rms-ratio").click(function (){
    $(this).parents('.rms-ratio-group').find('.rms-ratio').removeClass('active')
    $(this).addClass('active')
})


function animateFun($els){
    $els.each(function (){
        var scrollTop=$(window).scrollTop()+$(window).height();
        var section_top=$(this).offset().top       
        if(scrollTop>section_top+120){                  
            $(this).find(".zoomIn-animated").addClass('animate__animated').addClass('animate__zoomIn')
            $(this).find(".left-animated").addClass('animate__animated').addClass('animate__fadeInLeft')
            $(this).find(".right-animated").addClass('animate__animated').addClass('animate__fadeInRight')
            $(this).find(".fadeIn-animated").addClass('animate__animated').addClass('animate__fadeIn')
            $(this).find(".fadeInDown-animated").addClass('animate__animated').addClass('animate__fadeInDown')
            $(this).find(".fadeInUp-animated").addClass('animate__animated').addClass('animate__fadeInUp')
            $(this).find(".backInUp-animated").addClass('animate__animated').addClass('animate__backInUp')
            $(this).find(".bounceIn-animated").addClass('animate__animated').addClass('animate__bounceIn')
            $(this).find(".bounceInDown-animated").addClass('animate__animated').addClass('animate__bounceInDown')
            $(this).find(".bounceInUp-animated").addClass('animate__animated').addClass('animate__bounceInUp')
            $(this).find(".bounceInLeft-animated").addClass('animate__animated').addClass('animate__bounceInLeft')
            $(this).find(".bounceInRight-animated").addClass('animate__animated').addClass('animate__bounceInRight')
        }
    })
}

function animateFun2($els){
    $els.each(function (){
        var scrollTop=$(window).scrollTop()+$(window).height();
        var section_top=$(this).offset().top
        if(scrollTop>section_top+120){
            $(this).find(".zoomIn-animated2").addClass('animate__zoomIn')
            $(this).find(".left-animated2").addClass('animate__fadeInLeft')
            $(this).find(".right-animated2").addClass('animate__fadeInRight')
            $(this).find(".fadeIn-animated2").addClass('animate__fadeIn')
            $(this).find(".bounceInDown-animated2").addClass('animate__bounceInDown')
            $(this).find(".bounceInUp-animated2").addClass('animate__bounceInUp')
        }
    })
}

function initMenuClick(){
    var windowW = $('body').width()
    if(windowW<1000){
        $(".menu ul li .two_expand").each(function (){
            console.log(1111111,$(this).siblings('a').attr("href","javascript:;;"))
        })
    }
}
initMenuClick()