  (function($){
    "use strict";
   

    /*----------------------------------------------------*/
    /*  Owl Carousel
    /*----------------------------------------------------*/
    $(document).ready(function() {
      var owl = $(".owl-carousel");
      owl.owlCarousel({
         
          itemsCustom : [
            [0, 1],
            [450, 1],
            [600, 1],
            [700, 2],
            [1000, 2],
            [1200, 3],
            [1469, 3],
            [1600, 3]
          ],
          navigation : true,
          lazyLoad : true,
          pagination: false,
          navigationText: ["",""],
          
      });
    });     

 

})(this.jQuery);
