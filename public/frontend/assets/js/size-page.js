function initPage(page){
    setTimeout(()=>{
        initPage2(page)
    },100)
 }
 
 function initPage2(page){
     var h=$('.warp').height()
     $(".warp").show()
     var bodyH=$(".body").height()
 
     enlargePage(1960,bodyH)
 }