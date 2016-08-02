/*
    Jquery Widget for Homepage
*/


(function ($) {
    'use strict';
    $.widget('icube.customer', {

        _create: function () {
            this.initBoxAccount();
            this.initOrderTable();
            this.initEditAccount();
            this.initRedeem();
            this.initNewsletter();
        },

        _2columnEqualHeight: function(elmParent, elmChild1, elmChild2) {
            $(elmParent).each(function() {
                var col1box = $(this).find(elmChild1);
                var col2box = $(this).find(elmChild2);
                var col1boxH = col1box.outerHeight();
                var col2boxH = col2box.outerHeight();

                if (col1boxH > col2boxH) {
                    col2box.css('height',col1boxH+'px');
                }else{
                    col1box.css('height',col2boxH+'px');
                }
            });
        },

        initBoxAccount: function() {

            // make column within box-account have same height
            this._2columnEqualHeight('.box-account .col2-set', '.col-1 .box-content', '.col-2 .box-content');
        },

        initOrderTable: function() {

            $('.data-table td.ship').dotdotdot({
                watch: 'window'
            });
        },

        initEditAccount: function() {
            if ($('.section.customer-account-edit').length) {
                // make 2col-set columns have same height
                this._2columnEqualHeight('.section.customer-account-edit .col2-set', '.col-1', '.col-2');
            };
        },

        initRedeem: function() {
            if ($('.section.redeempoint').length) {
                $(document).redeem();
            };  
        },

        initNewsletter: function() {
            if ($('body.monkey-customer-account-index').length) {
                // make 2col-set columns have same height
                this._2columnEqualHeight('.monkey-multisubscribe .col2-set', '.col-1', '.col-2');
            };
        }
    });
}(jQuery));
