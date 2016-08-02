/*
    Jquery Widget for header
*/


(function ($) {
    'use strict';
    $.widget('icube.header', {

        _create: function () {
            this.initAnnouncementHeader();
            this.initMiniCart();
            this.initAccountLinks();
            this.initMiniTrackorder();
            this.initMegamenu();

            var _this = this;
            $('body').click(function(e) {
                if (!$(e.target).parents().andSelf().is('.skip-content')) {
                    _this._hideAllSkipContent();
                }
            });
        },

        initAnnouncementHeader: function() {

            /* -- Announcement Header -- */
            var announcementBlock = $('.header-announcement-background');

            // checking cookie
            var hideAnnouncementHeader = $.cookie('hideAnnouncementHeader');

            if (hideAnnouncementHeader) {
                announcementBlock.hide();
            }else{
                announcementBlock.show();
            }

            announcementBlock.find('.close-announcement').click(function() {
                
                // slide it up
                announcementBlock.slideUp();

                // set cookie
                var date = new Date();
                var time = 10; //expire after 5mins
                date.setTime(date.getTime() + (time * 60 * 1000));
                $.cookie('hideAnnouncementHeader', true, { expires: date });
            });

            // rolling contents
            $('.header-announcement-background .content-list').bxSlider({
                auto: true,
                pause: 5000,
                mode: 'fade',
                controls: false,
                pager: false
            });

        },

        _hideAllSkipContent: function() {
            $('.header-container .skip-content').hide();
        },

        initMiniCart: function() {

            /* -- Header Cart --*/
            // show/hide minicart
            // see icube.ajaxaddtocart.js
        },

        initAccountLinks: function() {
            var _this = this;

            /* -- Header account links --*/
            // show/hide myaccount-menu
            $j('.account-link .links.loggedin').click( 
                function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var menu = $(this).parent().find('.myaccount-menu');
                    if (!menu.is(':visible')) {
                        //hide minicart
                        _this._hideAllSkipContent(menu);

                        menu.fadeIn();
                    }else{
                        menu.hide();
                    }
                } 
            );
        },

        initMiniTrackorder: function() {
            var _this = this;

            /* -- Header account links --*/
            // show/hide myaccount-menu
            $j('#orderstatus-form-mini-link').click( 
                function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var content = $(this).parent().find('#orderstatus-form-mini');
                    if (!content.is(':visible')) {
                        //hide other skip-content
                        _this._hideAllSkipContent(content);

                        content.fadeIn();

                        content.find('#orderid').focus();
                    }else{
                        content.hide();
                    }
                } 
            );
        },

        initMegamenu: function() {
            var gridRows = []; // This will store an array per row
            var tempRow = [];
            var listsElement = $j('#header-nav ul.level0').children('li');
            listsElement.each(function (index) {
                // The JS ought to be agnostic of the specific CSS breakpoints, so we are dynamically checking to find
                // each row by grouping all cells (eg, li elements) up until we find an element that is cleared.
                // We are ignoring the first cell since it will always be cleared.
                if ($j(this).css('clear') != 'none' && index != 0) {
                    gridRows.push(tempRow); // Add the previous set of rows to the main array
                    tempRow = []; // Reset the array since we're on a new row
                }
                tempRow.push(this);

                // The last row will not contain any cells that clear that row, so we check to see if this is the last cell
                // in the grid, and if so, we add its row to the array
                if (listsElement.length == index + 1) {
                    gridRows.push(tempRow);
                }
            });

            $j.each(gridRows, function () {
                var tallestElmHeight = 0;
                $j.each(this, function () {
                    // Since this function is called every time the page is resized, we need to remove the min-height
                    // and bottom-padding so each cell can return to its natural size before being measured.
                    $j(this).css({
                        'min-height': ''
                    });

                    var height = $(this).height();
                    if (height > tallestElmHeight) {
                        tallestElmHeight = totalHeight;
                    }
                });
                // Set the height of all item in a row to the tallest height
                $j.each(this, function () {
                    $j(this).css('min-height', tallestElmHeight);
                });
            });
        }

    });
}(jQuery));
