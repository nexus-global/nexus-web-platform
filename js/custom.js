/*global  wpv */
(function($){
    "use strict";

    $(document).ready(function(){

  
    //  Navigation
  
    function menumobile(){
        var winWidth = $(window).width();
        if( winWidth < 973 ) {
            $('#navigation').removeClass('menu');
            $('#navigation li').removeClass('dropdown');
            $('#navigation').superfish('destroy');
        } else {
            $('#navigation').addClass('menu');
            $('#navigation').superfish({
                delay:       300,                               // one second delay on mouseout
                animation:   {opacity:'show', height:'show'},   // fade-in and slide-down animation
                speed:       200,                               // animation speed
                speedOut:    50                                 // out animation speed
            });
        }
    }

    $(window).resize(function (){
        menumobile();
    });
    menumobile();

    // Search
    $('li.search, .search-trigger').click(function(e){
      e.preventDefault();
      $('.header-search').fadeIn(150);
    });

    $('.header-search .close-search a').click(function(e){
      e.preventDefault();
      $('.header-search').fadeOut(150);
    });

    var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;

    $(window).on("load", function() {
      if (pixelRatio > 1) {
        if(wpv.retinalogo) {
          $('#logo img').attr('src',wpv.retinalogo);
        }
      }
    });



    /*----------------------------------------------------*/
    /*  Mobile Navigation
    /*----------------------------------------------------*/

    var jPanelMenu = $.jPanelMenu({
      menu: '#responsive',
      animated: true,
      duration: 200,
      keyboardShortcuts: false,
      closeOnContentClick: false
    });


    // detecting user agent
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    if(isMobile.any()){

      // mobile devices
      jPanelMenu.on();
      $(document).on('click',jPanelMenu.menu + ' li a',function(e){
        if ( jPanelMenu.isOpen() && $(e.target).attr('href').substring(0,1) === '#' ) { jPanelMenu.close(); }
      });

      $(document).on('touchend','.menu-trigger',function(e){
        jPanelMenu.triggerMenu();
        e.preventDefault();
        return false;
      });

    } else {

      // desktop devices
        $('.menu-trigger').click(function(){
          var jpm = $(this);

          if( jpm.hasClass('active') )
          {
            jPanelMenu.off();
            jpm.removeClass('active');
          }
          else
          {
            jPanelMenu.on();
            jPanelMenu.open();
            jpm.addClass('active');
          }
          return false;
        });
    }


    // Removes SuperFish Styles
    $('#jPanelMenu-menu').removeClass('sf-menu');
    $('#jPanelMenu-menu li ul').removeAttr('style');


    $(window).resize(function (){
      var winWidth = $(window).width();
      var jpmactive = $('.menu-trigger');
      if(winWidth>990) {
        jPanelMenu.off();
        jpmactive.removeClass('active');
      }
    });


    new View( $('.wp-caption a[href], a.view[href]') );

    /*----------------------------------------------------*/
    /*  Royal Slider
    /*----------------------------------------------------*/

      $(".royalSlider").royalSlider({

        autoScaleSlider: true,
        autoHeight: true,
        autoScaleSliderWidth: 1260,


        loop: false,
        imageScaleMode: 'fill',
        imageAlignCenter:false,

        navigateByClick: false,
        numImagesToPreload:2,

        fadeinLoadedSlide: true,
        arrowsNavAutoHide: false,

        arrowsNav: true,
        slidesSpacing: 4

      });  

      $('.front-slider').royalSlider({

        autoScaleSlider: true,
        autoScaleSliderHeight: "auto",
        autoHeight: true,

        loop: false,
        slidesSpacing: 0,

        imageScaleMode: 'none',
        imageAlignCenter:false,

        navigateByClick: false,
        numImagesToPreload:10,

        /* Arrow Navigation */
        arrowsNav:true,
        arrowsNavAutoHide: false,
        arrowsNavHideOnTouch: true,
        keyboardNavEnabled: true,
        fadeinLoadedSlide: true,

      });



    //  Back To Top Button
    //----------------------------------------//

    var pxShow = 600; // height on which the button will show
    var fadeInTime = 400; // how slow / fast you want the button to show
    var fadeOutTime = 400; // how slow / fast you want the button to hide
    var scrollSpeed = 400; // how slow / fast you want the button to scroll to top.

    $(window).scroll(function(){
      if($(window).scrollTop() >= pxShow){
        $("#backtotop_wpv").fadeIn(fadeInTime);
      } else {
        $("#backtotop_wpv").fadeOut(fadeOutTime);
      }
    });

    $('#backtotop_wpv a').click(function(){
      $('html, body').animate({scrollTop:0}, scrollSpeed);
      return false;
    });


    /*----------------------------------------------------*/
    /*  Photo Grid
    /*----------------------------------------------------*/

    $('body').imagesLoaded()
     .always( function() {
        $( ".fullscreen" ).fadeTo( 1000 , 1);
        
        $('.two').photoGrid({
          rowHeight: $(window).height() / 2
        });

        $('.three').photoGrid({
          rowHeight: $(window).height() / 3
        });          
        $('.four').photoGrid({
          rowHeight: $(window).height() / 4
        });         
        $('.five').photoGrid({
          rowHeight: $(window).height() / 5
        });          
        $('.six').photoGrid({
          rowHeight: $(window).height() / 6
        });  

     });
 $(window).resize(function (){
         $('.two').photoGrid({
          rowHeight: $(window).height() / 2
        });

        $('.three').photoGrid({
          rowHeight: $(window).height() / 3
        });          
        $('.four').photoGrid({
          rowHeight: $(window).height() / 4
        });         
        $('.five').photoGrid({
          rowHeight: $(window).height() / 5
        });          
        $('.six').photoGrid({
          rowHeight: $(window).height() / 6
        });  
    });


    /*----------------------------------------------------*/
    /*  Post Series Toggle
    /*----------------------------------------------------*/
    $('.post-series').each(function(){
      $(this).find('.post-series-links').hide();
    });

    $('.post-series a.show-more-posts').click(function(){
      var el = $(this), parent = el.closest('.post-series');

      if( el.hasClass('active') )
      {
        parent.find('ul.post-series-links').slideToggle(150);
        el.removeClass('active');
      }
      else
      {
        parent.find('ul.post-series-links').slideToggle(150);
        el.addClass('active');
      }
      return false;
    });



    /*----------------------------------------------------*/
    /*  Contact Form
    /*----------------------------------------------------*/
    $("#contactform .submit").click(function(e) {


      e.preventDefault();
      var user_name       = $('input[name=name]').val();
      var user_email      = $('input[name=email]').val();
      var user_comment    = $('textarea[name=comment]').val();

      //simple validation at client's end
      //we simply change border color to red if empty field using .css()
      var proceed = true;
      if(user_name===""){
          $('input[name=name]').addClass('error');
            proceed = false;
          }
          if(user_email===""){
            $('input[name=email]').addClass('error');
            proceed = false;
          }
          if(user_comment==="") {
            $('textarea[name=comment]').addClass('error');
            proceed = false;
          }

          //everything looks good! proceed...
          if(proceed) {
            $('.hide').fadeIn();
            $("#contactform .submit").fadeOut();
              //data to be sent to server
              var post_data = {'userName':user_name, 'userEmail':user_email, 'userComment':user_comment};

              //Ajax post data to server
              $.post('contact.php', post_data, function(response){
                var output;
                //load json data from server and output comment
                if(response.type == 'error')
                  {
                    output = '<div class="error">'+response.text+'</div>';
                    $('.hide').fadeOut();
                    $("#contactform .submit").fadeIn();
                  } else {

                    output = '<div class="success">'+response.text+'</div>';
                    //reset values in all input fields
                    $('#contact div input').val('');
                    $('#contact textarea').val('');
                    $('.hide').fadeOut();
                    $("#contactform .submit").fadeIn().attr("disabled", "disabled").css({'backgroundColor':'#c0c0c0', 'cursor': 'default' });
                  }

                  $("#result").hide().html(output).slideDown();
                }, 'json');
            }
      });

    //reset previously set border colors and hide all comment on .keyup()
    $("#contactform input, #contactform textarea").keyup(function() {
      $("#contactform input, #contactform textarea").removeClass('error');
      $("#result").slideUp();
    });



    /*----------------------------------------------------*/
    /*  Parallax by minimit.com
    /*----------------------------------------------------*/

    /* detect touch */
    if("ontouchstart" in window){
        document.documentElement.className = document.documentElement.className + " touch";
    }
    if(!$("html").hasClass("touch")){
        /* background fix */
        $(".parallax").css("background-attachment", "fixed");
    }

    /* resize background images */
    function backgroundResize(){
        var windowH = $(window).height();
        $(".background").each(function(){
            var path = $(this);
            // variables
            var contW = path.width();
            var contH = path.height();
            var imgW = path.attr("data-img-width");
            var imgH = path.attr("data-img-height");
            var ratio = imgW / imgH;
            // overflowing difference
            var diff = parseFloat(path.attr("data-diff"));
            diff = diff ? diff : 0;
            // remaining height to have fullscreen image only on parallax
            var remainingH = 0;
            if(path.hasClass("parallax") && !$("html").hasClass("touch")){
                //var maxH = contH > windowH ? contH : windowH;
                remainingH = windowH - contH;
            }
            // set img values depending on cont
            imgH = contH + remainingH + diff;
            imgW = imgH * ratio;
            // fix when too large
            if(contW > imgW){
                imgW = contW;
                imgH = imgW / ratio;
            }
            //
            path.data("resized-imgW", imgW);
            path.data("resized-imgH", imgH);
            path.css("background-size", imgW + "px " + imgH + "px");
        });
    }
    $(window).resize(backgroundResize);
    $(window).focus(backgroundResize);
    backgroundResize();


    /* set parallax background-position */
    function parallaxPosition(){
        var heightWindow = $(window).height();
        var topWindow = $(window).scrollTop();
        var bottomWindow = topWindow + heightWindow;
        var currentWindow = (topWindow + bottomWindow) / 2;
        $(".parallax").each(function(){
            var path = $(this);
            var height = path.height();
            var top = path.offset().top;
            var bottom = top + height;
            // only when in range
            if(bottomWindow > top && topWindow < bottom){
                //var imgW = path.data("resized-imgW");
                var imgH = path.data("resized-imgH");
                // min when image touch top of window
                var min = 0;
                // max when image touch bottom of window
                var max = - imgH + heightWindow;
                // overflow changes parallax
                var overflowH = height < heightWindow ? imgH - height : imgH - heightWindow; // fix height on overflow
                top = top - overflowH;
                bottom = bottom + overflowH;
                // value with linear interpolation
                var value = min + (max - min) * (currentWindow - top) / (bottom - top);
                // set background-position
                var orizontalPosition = path.attr("data-oriz-pos");
                orizontalPosition = orizontalPosition ? orizontalPosition : "50%";
                $(this).css("background-position", orizontalPosition + " " + value + "px");
            }
        });
    }
    if(!$("html").hasClass("touch")){
        $(window).resize(parallaxPosition);
        //$(window).focus(parallaxPosition);
        $(window).scroll(parallaxPosition);
        parallaxPosition();
    }


    // Scrolling
    $(".scroll-to-content").on('click touchstart', function() {
        $('html, body').animate({
            scrollTop: $("#single-page-container").offset().top
        }, 1000);
    });

  
    if(!$("html").hasClass("touch")){
      // Fading Title
      $(window).scroll(function(){
          $(".fullscreen-image-title").css("opacity", 1 - $(window).scrollTop() / 600);
      }); 
       $(window).load(function(){
          $(".fullscreen-image-title").css("opacity", 1 - $(window).scrollTop() / 600);
      }); 

      // PArallax Title Bar Fading Title
      $(window).scroll(function(){
          $(".parallax-title").css("opacity", 1 - $(window).scrollTop() / 400);
      }); 
       $(window).load(function(){
          $(".parallax-title").css("opacity", 1 - $(window).scrollTop() / 400);
      });
   }
  

    /*----------------------------------------------------*/
    /*  Tooltips
    /*----------------------------------------------------*/
    $(".tooltip.top").tipTip({
          defaultPosition: "top"
    });

    $(".tooltip.bottom").tipTip({
          defaultPosition: "bottom"
     });

     $(".tooltip.left").tipTip({
           defaultPosition: "left"
     });

    $(".tooltip.right").tipTip({
          defaultPosition: "right"
    });



    /*----------------------------------------------------*/
    /*  Tabs
    /*----------------------------------------------------*/

    var $tabsNav    = $('.tabs-nav'),
     $tabsNavLis = $tabsNav.children('li');
    // $tabContent = $('.tab-content');

    $tabsNav.each(function() {
     var $this = $(this);

        $this.next().children('.tab-content').stop(true,true).hide()
        .first().show();

        $this.children('li').first().addClass('active').stop(true,true).show();
     });

    $tabsNavLis.on('click', function(e) {
         var $this = $(this);

         $this.siblings().removeClass('active').end()
        .addClass('active');

        $this.parent().next().children('.tab-content').stop(true,true).hide()
        .siblings( $this.find('a').attr('href') ).fadeIn();

        e.preventDefault();
    });



    /*----------------------------------------------------*/
    /*  Accordions
    /*----------------------------------------------------*/

    var $accor = $('.accordion');

     $accor.each(function() {
        $(this).addClass('ui-accordion ui-widget ui-helper-reset');
        $(this).find('h3').addClass('ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all');
        $(this).find('div').addClass('ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom');
        $(this).find("div").hide().first().show();
        $(this).find("h3").first().removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-accordion-header-active ui-state-active ui-corner-top');
        $(this).find("span").first().addClass('ui-accordion-icon-active');
    });

    var $trigger = $accor.find('h3');

    $trigger.on('click', function(e) {
        var location = $(this).parent();

        if( $(this).next().is(':hidden') ) {
            var $triggerloc = $('h3',location);
            $triggerloc.removeClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideUp(300);
            $triggerloc.find('span').removeClass('ui-accordion-icon-active');
            $(this).find('span').addClass('ui-accordion-icon-active');
            $(this).addClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideDown(300);
        }
         e.preventDefault();
    });


 // ------------------ End Document ------------------ //
});

})(this.jQuery);


