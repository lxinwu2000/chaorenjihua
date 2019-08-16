var width = 0;
    $('#overflow .container div').each(function () { 
      width += $(this).outerWidth(true); 
    }); 
    $('#overflow .container').css('width', width + "px"); 
    var flag = true;
    $(".drop_down img").click(function(e){ //箭头符号的变化
      if(flag){
        $(".drop_down img").attr("src","img/icon_events_unfold.png");
        $(".tabUl").css("display","block")
        flag = false; 
      }else{
        $(".drop_down img").attr("src","img/icon_events_fold.png");
        $(".tabUl").css("display","none")
        flag = true;
      }
    });
    var ulHeight= Math.ceil(($(".tabUl li").length-1)/6)*2.65625 +"rem";
    $(".navScroll .tabUl").css("height",ulHeight)
    $(".navScroll").on("click",".tabItem",function(e,index){ //滑动栏点击样式      
      var $this=$(this);
      $(".tabItem").css({"color": "#444"})
      $($this).css({"color": "#FF9933"});
      var items = $($this)[0];
      var ulIndx;
      $(".tabItem").each(function(i,n){
        if(n==items){
          ulIndx = i;
        }
      })  
      $(".tabUl li").css({"color": "#444"});
      var ulItems = $(".tabUl li")[ulIndx];
      $(ulItems).css({"color": "#FF9933"});
      moveNav(ulIndx);
    })    
    $(".navScroll").on("click","li",function(e,index){ //下拉点击样式      
      var $this=$(this);
      $("li").css({"color": "#444"})
      $($this).css({"color": "#FF9933"});
      var items = $($this)[0];
      var ulIndx;
      $("li").each(function(i,n){
        if(n==items){
          ulIndx = i;
        }
      })  
      $(".tabItem").css({"color": "#444"});
      var ulItems = $(".tabItem")[ulIndx];
      $(ulItems).css({"color": "#FF9933"});
      $(".drop_down img").attr("src","img/icon_events_fold.png");
      $(".tabUl").css("display","none")
      flag = true;
      moveNav(ulIndx);
    })  
    function moveNav(index){ //滑动栏移动效果
      var allImg = $(".navScroll").find(".tabItem");
      var navImgWidth = allImg.width();
      var lastWidth = $(".container .lastWidth").width();
      var windowWidth = $(window).width();
      var navBox = $("#overflow");
      if(navImgWidth*(index+1) >=windowWidth-navImgWidth){
        if(navImgWidth*(index+1)<navImgWidth*(allImg.length-1)+lastWidth-windowWidth){
          navBox.animate({scrollLeft:navImgWidth*(index+1)},500);
        }else{
          navBox.animate({scrollLeft:navImgWidth*(allImg.length)+lastWidth-windowWidth},500);
        }
      }else{
        navBox.animate({scrollLeft:'0px'},1000);
      }
    }