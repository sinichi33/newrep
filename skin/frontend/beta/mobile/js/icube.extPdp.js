/*
    Jquery Widget for PDP

    Description:
    - extend widget $.icube.pdp
    - modify this.initUpsellSlider(), this.initRelatedSlider(), & initRecentViewedSlider(). load min 2 product per slide
*/

(function ($) {
    'use strict';

    $.extend(true, $.icube.pdp.prototype, {

        initUpsellSlider: function() {
            /* upsell slider */
            $('#upsell-product-table').bxSlider({
                minSlides: 2,
                maxSlides: 3,
                slideWidth: 200,
                slideMargin: 10,
                infiniteLoop: false,
                pager: false
            });
        },

        initRelatedSlider: function() {
            /* related products slider */
            $('#related-product-table').bxSlider({
                minSlides: 2,
                maxSlides: 3,
                slideWidth: 200,
                slideMargin: 10,
                infiniteLoop: false,
                pager: false
            });
        },

        initRecentViewedSlider: function() {
            /* recently viewed products slider */
            $('#recently-viewed-items').bxSlider({
                minSlides: 2,
                maxSlides: 3,
                slideWidth: 200,
                slideMargin: 10,
                infiniteLoop: false,
                pager: false
            });
        },

    });
    
}(jQuery));
