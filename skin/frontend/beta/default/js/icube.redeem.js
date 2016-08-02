/*
    Jquery Widget for PDP
*/


(function ($) {
    'use strict';
    $.widget('icube.redeem', {

        vars: {
            defaultCompany: 'ace'
        },

        _create: function () {
            this.initStep1();
            this.initStep2();
        },

        _selectTab: function(company) {
            // hide all form first
            $('.my-account .form-wrapper').hide();
            $('.company-selection a').removeClass('selected');

            // select desired tab
            $('.company-selection a.' + company).addClass('selected');
            $('#form-' + company).show();

            // show desired company title
            $('.section.redeempoint .title h3').hide();
            $('.section.redeempoint .title .'+company).show();
            $('.section.redeempoint .title h3 .your-point').text(' ');
        },

        _showPoints: function(company) {
            $('.point-ace, .point-informa, .point-toyskingdom').hide();
            $('.point-'+company).show();
        },
    
        refreshStep1: function() {
            // select default tab
            this._selectTab(this.vars.defaultCompany);

            // refresh company title
            $('.section.redeempoint .title h3').hide();
            $('.section.redeempoint .title .'+this.vars.defaultCompany).show();
            $('.section.redeempoint .title h3 .your-point').text(' ');
            $('.section.redeempoint .step-1 .messages').remove();

            // show points of default company
            this._showPoints(this.vars.defaultCompany);
        },      

        refreshStep2: function() {
            $('.section.redeempoint .step-2').addClass('disable');
            $('.section.redeempoint .step-2 input').prop('disabled',true);
            $('.section.redeempoint .step-2 .btn-popup-passkey').addClass("disable");
            $('.section.redeempoint .step-2.disable input').prop("disabled", true);
            $('.section.redeempoint .step-2.disable .button').prop("disabled", true);
            $('.section.redeempoint .step-2 input[type=checkbox]').prop('checked',false);
            $('.section.redeempoint .step-2 input[type=radio]').prop('checked',false);
            $('.section.redeempoint .step-2 input.input-text').val('');
            $('.section.redeempoint .step-2 .messages').remove();
        },

        initStep1: function() {
            var _this = this;

            _this.refreshStep1();

            // clicking tab
            $('.company-selection a').click(function() {
                var company = $(this).data('company');

                if (!$(this).hasClass('selected')) {
                    _this._selectTab(company);
                    _this.refreshStep2();
                    _this._showPoints(company);
                }

            });

            // submit form on pressing Enter
            $('#form-validate-ace input, #form-validate-informa input, #form-validate-toyskingdom input').keypress(function(event) {
                var _this = $(this);
                if (event.which == 13) {
                    event.preventDefault();
                    _this.closest('form').find('.btn-submit').click();
                }
            });

            //submit flow
            this.step1SubmitFlow();

        },

        step1SubmitFlow: function() {
            var _this = this;

            // ACE
            redeemformace.submit = function() 
            {
               callAjaxForm(this);
            }.bind(redeemformace);
            
            // Informa
            redeemforminforma.submit = function() 
            {
               callAjaxForm(this);
            }.bind(redeemforminforma);
            
            // Toys
            redeemformtoyskingdom.submit = function() 
            {
               callAjaxForm(this);
            }.bind(redeemformtoyskingdom);

            function callAjaxForm(varForm) 
            {
                if (varForm.validator.validate()) {
                    var form = varForm.form;

                    _this.refreshStep2();

                    new Ajax.Request(form.action, {
                        parameters: form.serialize(),
                        method     : 'post',
                        onLoading: function() {
                            //show loader
                            $('.section.redeempoint').addClass('loading');
                        },
                        onComplete : function(transport) {
                            var response = JSON.parse(transport.responseText);
                            var msg = $('<ul class="messages"></ul>');

                            // hide messages
                            $('.section.redeempoint .step-1 > .messages').remove();

                            if (response.status == 'success') {
                                $('.section.redeempoint .step-2').removeClass('disable');
                                $('.section.redeempoint .step-2 input').prop('disabled',false);

                                // get selected company
                                if (form.type.value == 'AHI') {
                                    var company = 'ace';
                                }
                                if (form.type.value == 'TGI') {
                                    var company = 'toyskingdom';
                                }
                                if (form.type.value == 'HCI') {
                                    var company = 'informa';
                                }

                                // set current point
                                $('.section.redeempoint .step-2 .'+company+' .your-point').text(response.point);

                                // show all points belong to selected company
                                _this._showPoints(company);
                            }else{
                                msg = msg.append('<li class="error-msg"><ul><li>'+response.message+'</ul></li></li>');
                                $('.section.redeempoint .step-1').prepend(msg);
                            }

                            // hide loader
                            $('.section.redeempoint').removeClass('loading');

                            // set hidden field of step 2
                            $('#form-select-voucher input[name=type]').val(form.type.value);
                            $('#form-select-voucher input[name=memberid]').val(form.memberid.value);
                        }
                    });
                }
            }
        },

        initStep2: function() {
            var _this = this;
            
            this.refreshStep2();

            $('#redeem-agree').change(function() {
                if($(this).prop('checked')) {
                    $('.section.redeempoint .step-2 .button').prop("disabled", false);
                }else{
                    $('.section.redeempoint .step-2 .button').prop("disabled", true);
                }
            });

            $('#form-select-voucher .btn-popup-passkey').click(function(){
                var that = $(this);
                if(validatorFormSelectVoucher1.validate()) {
                    that.removeClass('disable');
                }else{
                    that.addClass('disable');
                }
            });

            $('#redeem-confirm-success .btn-close').click(function() {
                $.magnificPopup.close();
                _this.refreshStep1();
                _this.refreshStep2();
            });

            // submit form on pressing Enter on passkey popup
            $('#form-select-voucher-1 input').keypress(function(event) {
                var _this = $(this);
                if (event.which == 13) {
                    event.preventDefault();
                }
            });
            $('#redeem-confirm-passkey input').keypress(function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                    $('#redeem-confirm-passkey .btn-submit').click();
                }
            });

            //submit flow
            this.step2SubmitFlow();
        },

        step2SubmitFlow: function() {
            formSelectVoucher.submit = function() {
                if (validatorFormSelectVoucher1.validate() && validatorFormSelectVoucher2.validate()) {
                    var form = formSelectVoucher.form;

                    new Ajax.Request(form.action, {
                        parameters: form.serialize(),
                        method     : 'post',
                        onLoading: function() {
                            jQuery('#redeem-confirm-passkey').hide();
                            jQuery('.section.redeempoint').addClass('loading');
                        },
                        onComplete : function(transport) {
							var response = JSON.parse(transport.responseText);
                            
                            var msg = $('<ul class="messages"></ul>');
                            $('.section.redeempoint .step-2 > .messages').remove();
                            
                            if (response.status == 'success') {
    							// update giftcard table
    							$('.data-table-wrapper').parent().html(response.html);
                                
                                // update success popup content
                                var selectedVoucherId = $("#form-select-voucher-1 input[type='radio']:checked").attr('id');
                                var selectedVoucherLabel = $('#form-select-voucher-1 label[for="'+selectedVoucherId+'"]').clone().children().remove().end().text();
                                $('#redeem-confirm-success-value span').text(selectedVoucherLabel);
                                $('#reeem-rest-points strong').text(response.point);

                                // open success popup
                                $.magnificPopup.open({
                                  items: {
                                    src: '#redeem-confirm-success',
                                    type: 'inline'
                                  }
                                });
                            }else{
                                msg = msg.append('<li class="error-msg"><ul><li>'+response.message+'</ul></li></li>');
                                $('.section.redeempoint .step-2').prepend(msg);
                            }

                            jQuery('.section.redeempoint').removeClass('loading');
                        }
                    });
                }
            }.bind(formSelectVoucher);
        }
    });
}(jQuery));
