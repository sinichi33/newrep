/*
    JQuery project main widget.
 */

(function ($) {
    'use strict';
    $.widget('icube.mobCatalogPopup', {

        _create: function () {
            if ($('.block-layered-nav').length) {
                this.filter();
            }else{
                $('.toolbar .filter-link').hide();
            }

            if ($('#sort-by-popup').length) {
                this.sortby();
            }else{
                $('.toolbar .sort-by-link').hide();
            }
        },

        sortby: function() {
            
            /* move currently sort by */
            var current_sortby = $('#currently-sort-by');
            var currect_sortbyText = $('input[name="sortby"]:checked + label').text();
            if ($('#currently-filter').length) {
                current_sortby.insertAfter($('#currently-filter'));
            } else {
                current_sortby.insertBefore($('.main .category-products > .toolbar:first-child'));
                $('.toolbar .sort-by-link').css('width','100%');
            };
            current_sortby.find('.value').text(currect_sortbyText);
            current_sortby.css('display','');

            var elmPopup = $('#sort-by-popup');

            elmPopup.mmenu({
                extensions  : [ "pageshadow" ],
                offCanvas   : {
                    position    : "bottom",
                    zposition   : "front"
                },
                navbars: [
                    {
                        content: elmPopup.find('.buttons-set'),
                        position: 'bottom'
                    }
                ]
            });

            var sortbyPopup = elmPopup.data( "mmenu" );
            
            elmPopup.find('button.cancel').click(function() {
                sortbyPopup.close();
            });

            sortbyPopup.bind("open", function() {
                // set height of sortby popup 
                if ($('.header-announcement-background:visible').length) {
                    var h = $(window).height() - ($('.main-header-background').height() + $('.header-announcement-background').height());
                }else{
                    var h = $(window).height() - ($('.main-header-background').height());
                }
                elmPopup.css('height', h+'px');
            });

            // remove css display once document is ready
            elmPopup.css('display','');
        },

        filter: function() {

            /* move currently filter */
            var current_filter = $('#currently-filter');
            current_filter.insertBefore($('.main .category-products > .toolbar:first-child'));

            /* Prepare popup elm */
            $('.block-layered-nav').wrap('<div id="filter-popup" class="mmenu-popup"><div></div></div>');
            var elmPopup = $('#filter-popup');

            elmPopup.mmenu({
                extensions  : [ "pageshadow" ],
                offCanvas   : {
                    position    : "bottom",
                    zposition   : "front"
                },
                navbars: [
                    {
                        content: ['<div class="mmenu-popup-head"><i class="fa fa-filter"></i><span class="text">Filter</span><a href="javascript:void(0)" id="current_filter_clearall">Clear Filter</a></div>']
                    },
                    {
                        content: elmPopup.find('.buttons-set'),
                        position: 'bottom'
                    }
                ]
            });

            // navbar text limiter
            elmPopup.find(".mm-navbar .text").mCustomScrollbar();

            var filterPopup = $("#filter-popup").data( "mmenu" );
            
            $('#filter-popup .mm-next + a').click(function(event) {
                var nextArrow = $(this).parent().find('.mm-next');
                event.preventDefault();
                nextArrow.click();
            });

            elmPopup.find('button.cancel').click(function() {

                /* check whether sub list opened or not */
                if (!$('#filter-popup .mm-panel.mm-subopened').length) {
                    filterPopup.close();
                }else{
                    $('#filter-popup .mm-panel.mm-current .mm-prev').click();
                }
            });

            filterPopup.bind("open", function() {
                // set height of sortby popup 
                if ($('.header-announcement-background:visible').length) {
                    var h = $(window).height() - ($('.main-header-background').height() + $('.header-announcement-background').height());
                }else{
                    var h = $(window).height() - ($('.main-header-background').height());
                }
                elmPopup.css('height', h+'px');
            });

            filterPopup.bind("openedPanel", function() {
                // set navbar text
                var subElmOpened = elmPopup.find('.mm-subopened').length;
                var textElm = elmPopup.find('.mm-subopened .mm-selected .mm-next + a');
                var text = 'Filter';
                if (subElmOpened) {
                    text = text+' by <span class="orange">'+textElm.text()+'</span>';
                };
                
                elmPopup.find('.mmenu-popup-head .text').html(text);
            });
        }
    });
}(jQuery));
