/*
    JQuery project main widget.
 */

(function ($) {
    'use strict';
    $.widget('icube.mobile', {

        _create: function () {
            this.initAllPages();
            this.initCategoryPage();
            this.initFlashPage();
            this.initSearchPage();
            this.initProductPage();
            this.initCheckout();
            this.initCustomer();
            this.initCmsPage();
            this.initRma();
        },

        initAllPages: function() {
            
            /* destroy mscrollbar on filter */
            $(".sidebar #narrow-by-list dd").not('.not-attr').mCustomScrollbar('destroy');
            
            /* Mobile Menu */
            $('nav#mobile-menu').mmenu({
                extensions: [
                  "pagedim-black"
                ],
                navbars: [{
                    content: ['<div class="mmenu-head"><img src="'+SKINURL+'images/logo-invert.png" alt="Ruparupa" /></div>']
                }]
            });

            $('nav#mobile-menu li.category > a.category-link, nav#mobile-menu a.has-children').click(function(event) {
                var nextArrow = $(this).parent().find('.mm-next');
                event.preventDefault();
                nextArrow.click();
            });

            /* Toogle Search */
            $('header a.mobile-search').click(function() {
                if ($('#header-search').is(':visible')) {
                    $('#header-search span.triangle').fadeToggle(100);
                    $('#header-search').slideToggle(400);
                }else{
                    $('#header-search').slideToggle(400);
                    $('#header-search span.triangle').delay(200).fadeToggle();
                }
            });

            /* Back to Top */
            $('#back-to-top').click(function() {
                _helper.scrollToElm($('body'));
            });
        },

        initCategoryPage:function() {
            
            if ($('body.catalog-category-view').length) {
                $(document).mobCatalogPopup();
                // $(document).catalog_filter(); // custom Amasty layered nav
            }
        },

        initFlashPage:function() {

            if ($('body.attributesplash-page-view').length) {
                $(document).mobCatalogPopup();
            }
        },

        initSearchPage: function() {

            if ($('body.catalogsearch-result-index, body.wordpress-search-index').length) {
                $(document).mobCatalogPopup();
                // $(document).catalog_filter(); // custom Amasty layered nav
            }
        },

        initProductPage: function() {

            if ($('body.catalog-product-view').length) {
                $(document).mobPdp();
            }
        },

        initCheckout: function() {

            if ($('body.checkout-onepage-index').length) {
                
                /* toggle coupon form - on doc ready */
                var checked = $('#toggle-coupon-form').is(':checked');
                if (checked) {
                    $('#toggle-coupon-form').closest('.discount-form').find('.right').show();
                }else{
                    $('#toggle-coupon-form').closest('.discount-form').find('.right').hide();
                }
                /* toggle coupon form - on click */
                $('#toggle-coupon-form').click(function() {
                    var checked = $(this).is(':checked');

                    if (checked) {
                        $('#toggle-coupon-form').closest('.discount-form').find('.right').show();
                    }else{
                        $('#toggle-coupon-form').closest('.discount-form').find('.right').hide();
                    }
                });

                /* toggle giftcard form - on doc ready */
                var checked = $('#toggle-giftcard-form').is(':checked');
                if (checked) {
                    $('#toggle-giftcard-form').closest('#giftcard-form').find('.right').show();
                    $('#toggle-giftcard-form').closest('#giftcard-form').find('.check-gc-status').show();
                }else{
                    $('#toggle-giftcard-form').closest('#giftcard-form').find('.right').hide();
                    $('#toggle-giftcard-form').closest('#giftcard-form').find('.check-gc-status').hide();
                }
                /* toggle giftcard form - on click */
                $('#toggle-giftcard-form').click(function() {
                    var checked = $(this).is(':checked');

                    if (checked) {
                        $('#toggle-giftcard-form').closest('#giftcard-form').find('.right').show();
                        $('#toggle-giftcard-form').closest('#giftcard-form').find('.check-gc-status').show();
                    }else{
                        $('#toggle-giftcard-form').closest('#giftcard-form').find('.right').hide();
                        $('#toggle-giftcard-form').closest('#giftcard-form').find('.check-gc-status').hide();
                    }
                });

                /* Move loader & back link position */
                $('#opc-shipping .buttons-set .please-wait, #opc-payment .buttons-set .please-wait, #opc-shipping_method .buttons-set .please-wait').each(function() {
                    $(this).prependTo($(this).parent());
                });
                $('#opc-shipping .buttons-set .back-link, #opc-payment .buttons-set .back-link, #opc-shipping_method .buttons-set .back-link').each(function() {
                    $(this).appendTo($(this).parent());
                });
            }
        },

        initCustomer: function() {
            
            if ($('body.customer-account').length) {
                
                /* Account Menu */
                var accMenu = $('.sidebar .block-account');
                if (accMenu.length) {
                    accMenu.append('<div class="block-content" id="mob-active-menu"><ul></ul></div>');
                    $('#mob-active-menu').append(accMenu.find('.block-content:not(#mob-active-menu) .current').clone());
                };
                $('#mob-active-menu').click(function(e) {
                    e.preventDefault();
                    if (accMenu.find('.block-content:not(#mob-active-menu)').is(':visible')) {
                        $('#mob-active-menu').removeClass('clicked');
                    }else{
                        $('#mob-active-menu').addClass('clicked');
                    }
                    accMenu.find('.block-content:not(#mob-active-menu)').slideToggle();
                });

                /* Form Account Edit */
                if ($('.customer-account-edit').length) {
                    $('#change_password').closest('li').appendTo($('#change_password').closest('.fieldset').find('.col-2 .form-list'));
                }

                /* Redeem */
                if ($('.section.redeempoint').length) {
                    /* Select Company */
                    $('.company-selection .current ul').html($('.company-selection > ul li a.selected').parent().clone());
                    $('.company-selection > ul li a.selected').parent().hide();
                    $('.company-selection .current').click(function(e) {
                        $('.company-selection > ul').slideToggle();
                    });
                    $('.company-selection > ul li a').click(function(e) {
                        $('.company-selection .current ul').html($('.company-selection > ul li a.selected').parent().clone());
                        $('.company-selection > ul li').show();
                        $('.company-selection > ul li a.selected').parent().hide();
                        $('.company-selection > ul').slideToggle();
                    });
                }
            }

            /* registration */
            if ($('body.customer-account-create').length) {
                
                // regist member id
                $('#onoff-customer').change(function() {

                    if ($(this).prop("checked")) {
                        $('#integratememberid-yes').click();;
                    }
                    else {
                        $('#integratememberid-no').click();;
                    }
                });
                $('#onoff-customer').click();
            }
        },

        initCmsPage: function() {

            if ($('body.cms-page-view').length) {
            }

            if ($('#cms-nav').length) {
                /* Account Menu */
                var cmsMenu = $('#cms-nav');
                cmsMenu.append('<div id="mob-active-cmsmenu"><ul></ul></div>');
                $('#mob-active-cmsmenu').append(cmsMenu.find('.block-content .active').clone());
                $('#mob-active-cmsmenu').click(function(e) {
                    e.preventDefault();
                    if (cmsMenu.find('.block').is(':visible')) {
                        $('#mob-active-cmsmenu').removeClass('clicked');
                    }else{
                        $('#mob-active-cmsmenu').addClass('clicked');
                    }
                    cmsMenu.find('.block').slideToggle();
                });
            }
        },

        initRma: function() {
            
            if ($('body.icube-rma-return-search').length) {

                $('.user-not-logged-in-tabs h4').click(function() {

                    $('.user-not-logged-in-tabs > div').removeClass('active');
                    $(this).parent().addClass('active');

                    if ($(this).parent().hasClass('col-1')) {
                        $('.user-not-logged-in .col-1').show();
                        $('.user-not-logged-in .col-2').hide();
                    }else{
                        $('.user-not-logged-in .col-1').hide();
                        $('.user-not-logged-in .col-2').show();
                    }
                });
            }

            if ($('body.icube-rma-return-create').length) {

            }
        }
    });
}(jQuery));
