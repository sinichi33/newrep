/*
    JQuery project main widget.
 */

(function ($) {
    'use strict';
    $.widget('icube.main', {

        _create: function () {
            this.initAllPages();
            this.initHomePage();
            this.initCategoryPage();
            this.initProductPage();
            this.initSearchPage();
            this.initShoppingCartPage();
            this.initCmsPage();
            this.initMyAccountPage();
            this.initCustomer();
            this.initCheckoutPage();
            this.initRma();
            this.initStoreLocator();
            this.initLandingPage();

            this.initCustomPopup();
            this.initAllAccordion();
            this.initInspirationPagination();
        },

        initAllPages: function() {

            // header
            $(document).header();

            // custom scrollbar
            $("#header-cart #cart-sidebar").mCustomScrollbar();
            $(".sidebar #narrow-by-list dd").not('.not-attr').mCustomScrollbar();

            // tooltip
            $('.tooltip-warranty').tooltipster({
                maxWidth: 310,
                contentAsHTML: true,
                position: 'right'
            });

            /* -- product list -- */
            // limit product name
            $('.products-grid .product-name, .products .product-name, .header-minicart .product-details .product-name > a, .flash-sale .product-name').dotdotdot({
                watch: 'window'
            });
        },

        initHomePage: function() {

            if ($('body.cms-index-index').length) {
                $(document).homepage();
            }
        },

        initCategoryPage: function() {

            if ($('body.catalog-category-view').length) {
            }
        },

        initProductPage: function() {

            if ($('body.catalog-product-view').length) {
                $(document).pdp();
            }
        },

        initSearchPage: function() {

            if ($('body.catalogsearch-result-index, body.wordpress-search-index').length) {
            }
        },

        initShoppingCartPage: function() {

            if ($('body.checkout-cart-index').length) {

                // Select store popup
                // trigger clicked
                $('.select-store-link').closest('label').each(function() {
                    var _this = $(this);
                    var _id = $(this).find('.select-store-link').attr('id');
                    var _itemId = _id.replace('select-store-link-','');

                    _this.magnificPopup({
                        items: {
                            src: '#select-store-popup-'+_itemId,
                            type: 'inline'
                        },
                        closeOnBgClick: false,
                        enableEscapeKey: false
                    });
                });

                $('.select-store-popup-wrapper').each(function() {
                    var _this = $(this);
                    var _productId = $(this).attr('id').replace('select-store-popup-','')

                    // custom scroll bar
                    _this.find('.store-list').mCustomScrollbar();

                    // selecting store from list
                    _this.find('.store-list li:not(.disable)').click(function() {
                        var _selectedStoreId = $(this).data('storeid');
                        $(this).parent().find('> li').removeClass('selected');
                        $(this).addClass('selected');
                        $('#select-store-'+_productId).val(_selectedStoreId);
                    });                    
                });

                // remove selected store if user click Delivery to Home
                $('#shopping-cart-table .sp-methods input.s_method_delivery').change(function() {
                    var _id = $(this).attr('id');
                    var _itemId = _id.replace('s_method_delivery_','');
                    
                    if ($(this).is(':checked')) {
                        jQuery('#select-store-'+_itemId).val('');
                        jQuery('#select-store-popup-'+_itemId+' .store-list li').removeClass('selected');
                    }
                });

                // hide validation advice on selecting method
                // another show/hide validation advice are on :
                // - checkout/onepage/link.phtml
                // - checkout/cart/item.phtml
                $('#shopping-cart-table .sp-methods input.radio').change(function() {
                    $(this).closest('td.shipping').find('.validation-advice').fadeOut();
                });
            }
        },

        initCheckoutPage: function() {

            if ($('body.checkout-onepage-index').length) {
                /* Login Step */
                $('#custom-guest-checkout-button').click(function() {
                    $('input[name=checkout_method][value="guest"]').click();
                    $('#onepage-guest-register-button').click();
                });
                $('#custom-register-checkout-button').click(function() {
                    $('input[name=checkout_method][value="register"]').click();
                    $('#onepage-guest-register-button').click();
                });

                // tooltip
                $('.tooltip-giftcard').tooltipster({
                    maxWidth: 310,
                    contentAsHTML: true
                });
                $('.tooltip-coupon').tooltipster({
                    maxWidth: 310,
                    contentAsHTML: true
                });
            };
        },

        initCmsPage: function() {

            if ($('body.cms-cashback-100000').length) {
                $('#section-featured_products .products-grid').bxSlider({
                    minSlides: 5,
                    maxSlides: 5,
                    slideWidth: 200,
                    slideMargin: 20,
                    pager: false
                });
            }
 
            if ($('body.cms-promo-mandiri').length) {
                $('#section-featured_products .products-grid').bxSlider({
                    minSlides: 5,
                    maxSlides: 5,
                    slideWidth: 200,
                    slideMargin: 20,
                    pager: false
                });
            }

            if ($('body.cms-page-view').length) {
            }

            if ($('#cms-nav').length) {

                if (!$('body').hasClass('contacts-index-index')) {
                    // move page title
                    if ($('.main > .col-left-first').length) {
                        $('.col-main .page-title').insertBefore($('.main > .col-left-first'));
                    }else{
                        $('.col-main .page-title').insertBefore($('.main > .col-main'));
                    };
                };

                // set active menu
                var curUrlPath = location.pathname;
                    
                $('#cms-nav a').each(function() {
                    var path = $(this)[0].pathname;

                    var modulepath = path.split('/');
                    modulepath = modulepath[1];
                    var moduleCurUrlPath = curUrlPath.split('/');
                    moduleCurUrlPath = moduleCurUrlPath[1];
  
                    if (modulepath == moduleCurUrlPath) {
                        $(this).closest('li').addClass('active');
                        return false;
                    }else{
                        // looking for alternative path/module
                        var altpath = $(this).data('altpath');

                        if (altpath == moduleCurUrlPath) {
                            $(this).closest('li').addClass('active');
                            return false;
                        }
                    }
                });
            }
        },

        initMyAccountPage: function() {
            if ($('body.customer-account').length) {
                $(document).customer();
            }
        },

        initCustomer: function() {
            if ($('body.customer-account-create').length) {
                // tooltip
                $('.tooltip-email').tooltipster({
                    maxWidth: 225
                });
                
                $('.tooltip-rememberme').tooltipster({
                    maxWidth: 225,
                    contentAsHTML: true,
                    content: $('.remember-me-popup-body p')
                });
                
                // regist member id
                $('input[type=radio][name=integratememberid]').change(function() {
                    if (this.value == 'yes') {
                        $('.account-create li.memberid').show();
                    }
                    else if (this.value == 'no') {
                        $('.account-create li.memberid').hide();
                    }
                });
                $('#integratememberid-yes').click();
            }
        },


        initRma: function() {

            // order search 
            if ($('body.icube-rma-return-search').length) {
                
                // login
                $('#rma-btn-login').click(function() {
                    var loginBox = $('header .account-link .skip-content');
                    var loginBoxTrigger = $('header .account-link .links > a');
                    
                    if (!loginBox.is(':visible')) {
                        loginBoxTrigger.click();
                        _helper.scrollToElm(loginBox,50);
                    };
                });
            }
        },

        initStoreLocator: function() {

            if ($('body.clarion-storelocator-index-index').length || $('body.clarion-storelocator-index-search').length) {

                $('#stores-list-div').mCustomScrollbar();
            }
        },

        initLandingPage:function() {

            if ($('body.attributesplash-page-view').length) {

                $('.col-main .page-title').insertBefore($('.main > .col-main'));
                $('.col-main .splash-description').insertBefore($('.main > .col-main'));
            }
        },

        initCustomPopup: function() {

            $('.custom-popup-trigger').each(function() {
                var _this = $(this);
                var target = _this.data('target');

                _this.click(function() {
                    if (!$(this).hasClass('disable')) {
                        $('#'+target).fadeIn(200);
                    };
                });
            });

            $('.custom-popup-wrapper').each(function(){
                var _this = $(this);
                
                // close popup while clicking background
                _this.find('.bg').click(function() {
                    if (!$(this).hasClass('disable-close')) {
                        _this.fadeOut(200);
                    };
                });

                // positioning to center
                var content = _this.find('.popup-content');
                var height = content.outerHeight();
                var width = content.outerWidth();

                content.css({
                    'margin-top': '-'+parseInt(content.outerHeight()/2)+'px',
                    'margin-left': '-'+parseInt(content.outerWidth()/2)+'px'
                })
            });
        },

        initAllAccordion:function() {
            var base = this;

            $('.accordion').each(function(){

                // hide all block-content as default
                $(this).find('.block .block-content').hide();

                // event click binding
                $(this).find('.block-title').unbind('click');
                $(this).find('.block-title').click(function(){
                  var isExtended = $(this).hasClass('extended');

                  if (isExtended) {
                    base.accordionCloseOtherTabs($(this).parent().parent(), $(this).parent());
                    $(this).removeClass('extended');
                    $(this).next().slideUp(800, 'easeInOutCirc');
                  }else{
                    base.accordionCloseOtherTabs($j(this).parent().parent(), $(this).parent());
                    $(this).addClass('extended');
                    $(this).next().slideDown(800, 'easeInOutCirc');
                  }
                })
            });
        },

        accordionCloseOtherTabs:function(acordion,item){
            acordion.find('.block').not(item).find('.block-content').slideUp(800, 'easeInOutCirc');
            acordion.find('.block').not(item).find('.block-title').removeClass('extended');
        },

        initInspirationPagination:function(){
           if ($('.category-inspirations').length) {
               $('#section-inspirations').pajinate({
                   items_per_page : 4,
                   item_container_id : '.inspiration-list',
                   nav_panel_id : '.inspiration-pagination'
                   
               });
           }
       }
    });
}(jQuery));
