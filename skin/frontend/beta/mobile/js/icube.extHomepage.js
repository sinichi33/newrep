/*
    Jquery Widget for Homepage

    Description:
    - extend widget $.icube.homepage
    - modify initFeaturedProducts(). load 2 product per slide
*/

(function ($) {
    'use strict';

    $.extend(true, $.icube.homepage.prototype, {

        initFeaturedProducts: function() {

            $('#section-featured_products .products-grid').bxSlider({
                minSlides: 2,
                maxSlides: 2,
                slideWidth: 600,
                slideMargin: 10,
                pager: false
            });

        }
    });
    
}(jQuery));
