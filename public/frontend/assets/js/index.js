var mySwiper = new Swiper('.banner-container',{
    effect : 'fade',
    speed:2000,
    //loop: true,
    initialSlide: 1,  // 这里是重点。。
    autoplay: {
        delay: 4000,
    },
    on: {
        init: function () {
            //initAnimate(0)
        },
        slideChangeTransitionStart: function (swiper) {
            console.log(11111,this.realIndex)
            //initAnimate(this.activeIndex)
        },
        touchEnd: function () {
            //你的事件
        },
    },
})

function initAnimate(activeIndex){
    console.log(activeIndex)
    if(activeIndex==0){
        return
    }
    $(".swiper-slide").removeClass("animate-banner")
    $(".swiper-slide:eq("+activeIndex+")").addClass("animate-banner")
}



$("#showSearch").click(function (event){
    event.stopPropagation()
    $(".search-box").css("display","flex")
})
$(".search-box").click(function (event){
    event.stopPropagation()
})
$("body").click(function (){
    $(".search-box").hide()
})


var options = {
    useEasing: true,
    useGrouping: true,
    separator: ',',
    decimal: '.',
    prefix: '',
    suffix: ''
};

var scrollNumberArr=[]
$(".number-one .number span").each(function (){
    var demo = new CountUp($(this).get(0), 0, $(this).text(), 0, 1, options);
    scrollNumberArr.push(demo)
})


$(window).scroll(function () {
    // 数字动画
    const sctop=$(window).scrollTop()
    var top=$(".number-box-content").offset().top-$(window).height()+150
    console.log(sctop,top)
    if (sctop >=top) {
        for (var i in scrollNumberArr){
            scrollNumberArr[i].start()
        }
    }

    //首页滚动动画
    animateIndex($("section"))
   
    // $("section").each(function (){
    //     var scrollTop=$(window).scrollTop()+$(window).height();
    //     var section_top=$(this).offset().top
    //     if(scrollTop>section_top+180){
    //         //$(this).find(".page-title").addClass('animate__animated').addClass('animate__zoomIn')
    //         $(this).find(".zoomIn-animated").addClass('animate__animated').addClass('animate__zoomIn')
    //         $(this).find(".left-animated").addClass('animate__animated').addClass('animate__fadeInLeft')
    //         $(this).find(".right-animated").addClass('animate__animated').addClass('animate__fadeInRight')
    //         $(this).find(".fadeIn-animated").addClass('animate__animated').addClass('animate__fadeIn')
    //         $(this).find(".fadeInDown-animated").addClass('animate__animated').addClass('animate__fadeInDown')
    //         $(this).find(".fadeInUp-animated").addClass('animate__animated').addClass('animate__fadeInUp')
    //         $(this).find(".backInUp-animated").addClass('animate__animated').addClass('animate__backInUp')
    //         $(this).find(".bounceIn-animated").addClass('animate__animated').addClass('animate__bounceIn')
    //         $(this).find(".bounceInDown-animated").addClass('animate__animated').addClass('animate__bounceInDown')
    //         $(this).find(".bounceInUp-animated").addClass('animate__animated').addClass('animate__bounceInUp')
    //         $(this).find(".bounceInLeft-animated").addClass('animate__animated').addClass('animate__bounceInLeft')
    //         $(this).find(".bounceInRight-animated").addClass('animate__animated').addClass('animate__bounceInRight')
    //     }
    //     console.log(top)
    // })
})

$(".number-one .open").click(function (){
    $(".number-box").addClass('open')
    $(".number-two .number span").each(function (){
        var demo = new CountUp($(this).get(0), 0, $(this).text(), 0, 1, options);
        demo.start()
    })
})

var swiperNews =null
function initNewSwiper(){
    var swiperCount=4
    if($(window).width()<=1380){
        swiperCount=2
    }
    if($(window).width()<=750){
        swiperCount=1
    }
    if(swiperNews){
        swiperNews.destroy()
    }
    swiperNews=new Swiper(".news-container", {
        slidesPerView: swiperCount,
        spaceBetween: 0,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
}
initNewSwiper()
$(window).resize(function () {
    initNewSwiper()
})
var arr=[1,2,3,4,5,6,7,8,9,10,11];
setInterval(function (){
    if(arr.length==0){
        arr=[1,2,3,4,5,6,7,8,9,10,11];
    }
    var index=parseInt(arr.length*Math.random())
    var r=arr[index]
    arr.splice(index,1)
    $(".home-page5 .circle").removeClass('animation')
    $(".home-page5 .circle.num"+r+"").addClass('animation')
},500)
//动画js


function animateIndex($els){
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

animateIndex($("section"))