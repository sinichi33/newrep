/*
    Jquery Widget for Homepage
*/


(function ($) {
    'use strict';
    $.widget('icube.homepage', {

        _create: function () {
            this.initBannerSlider();
            this.initBrandsSlider();
            this.initFeaturedProducts();
        },

        initBannerSlider: function() {

            // enquire.register(_responsive.mqMobile, {
                
            //     match : function() {
            //     },
                
            //     unmatch : function() {
            //     }
            // });

            // $('#section-banner-slider > ul').owlCarousel({
            //     singleItem  : true,
            //     navigation  : true,
            //     autoPlay    : 5000
            // });

            $('#section-banner-slider > ul').bxSlider({
                auto: true,
                pause: 5000,
                mode: 'fade',
                onSliderLoad: function() {
                    var wrapper = $('#section-banner-slider .bx-wrapper');
                    var pager = wrapper.find('.bx-pager');
                    pager.wrap( "<div class='pager-wrapper'></div>" );
                }
            });
        },

        initFeaturedProducts: function() {

            // $('#section-featured_products .products-grid').owlCarousel({
            //     navigation  : true,
            //     items       : 5
            //     // autoPlay    : 5000
            // });

            $('#section-featured_products .products-grid').bxSlider({
                minSlides: 5,
                maxSlides: 5,
                slideWidth: 200,
                slideMargin: 20,
                pager: false
            });

        },

        initBrandsSlider: function() {

            // $('#section-brands #brands-slider').owlCarousel({
            //     singleItem  : true,
            //     navigation  : false,
            //     autoPlay    : 5000,
            //     afterInit : function(elem){
            //       var that = this
            //       that.owlControls.prependTo(elem)
            //     }
            // });

            $('#section-brands #brands-slider').bxSlider({
                auto: true,
                onSliderLoad: function() {
                    var wrapper = $('#section-brands .bx-wrapper');
                    var controls = wrapper.find('.bx-controls');
                    wrapper.prepend(controls);
                }
            });
        }
    });
}(jQuery));
