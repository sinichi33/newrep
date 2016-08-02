jQuery(document).ready(function(){
    jQuery('#horizontal-opc-nav').removeClass('hidden');

    /* if skip billing */
    if (isSkipBilling) {
        jQuery('#opc-shipping .opc-content-wrapper').addClass('loading');
    };
});  

// Override fn: initialization()
// Description: 
// - Bind click event of horizontal opc nav
// - Set first section as active section on nav
Checkout.prototype.initialize = function(accordion, urls) {
    this.accordion = accordion;
    this.progressUrl = urls.progress;
    this.reviewUrl = urls.review;
    this.saveMethodUrl = urls.saveMethod;
    this.failureUrl = urls.failure;
    this.billingForm = false;
    this.shippingForm= false;
    this.syncBillingShipping = false;
    this.method = '';
    this.payment = '';
    this.loadWaiting = false;
    this.steps = ['login', 'billing', 'shipping', 'shipping_method', 'payment', 'review'];
    //We use billing as beginning step since progress bar tracks from billing
    this.currentStep = 'billing';

    var first_section = '';

    this.accordion.sections.each(function(section,i) {
        if (i==0) {
            first_section = $(section).id.replace('opc-','');
        };
        Event.observe($(section).down('.step-title'), 'click', this._onSectionClick.bindAsEventListener(this));
    }.bind(this));

    // icube: set first section as active secion on nav
    if (first_section) {
        this.setHorizontalNavActive(first_section);
    };

    this.accordion.disallowAccessToNextSections = true;

    // icube: Bind click event of horizontal opc nav
    $$('#horizontal-opc-nav .section').each(function(section) {
        Event.observe($(section), 'click', this._onHorizontalNavClick.bindAsEventListener(this));
    }.bind(this));
}

// New fn: setHorizontalNavSectionActive()
// Description: 
// - Built for updating the horizontal opc nav active section
Checkout.prototype.setHorizontalNavActive = function(section) {
    
    // reset class
    $$('#horizontal-opc-nav .section').each(function(_section){
        _section.removeClassName('active');
        _section.removeClassName('allow');
        _section.removeClassName('last-allow');
    });

    //set 'active' class
    $('horizontal-opc-nav-' + section).addClassName('active');

    //set 'allow' class
    $$('#horizontal-opc-nav .section').each(function(_section){
        if (!_section.hasClassName('active')) {
            _section.addClassName('allow');
        }else{
            var prev = _section.previous();
            if (prev) {
                prev.addClassName('last-allow');
            };
            throw $break;
        }
    });
}
    
// New fn: horizontalNavClicked()
// Description: 
// - Horizontal OPC nav on click event.
// - The default section title (hidden) 'click' event will be triggered once user click the horizontal OPC nav
Checkout.prototype._onHorizontalNavClick = function(event) {
    var sectionElm  = $(Event.element(event)).up();
    var section     = sectionElm.id.replace('horizontal-opc-nav-', '');
    var sectionId   = 'opc-' + section;

    if ($(sectionId).hasClassName('allow')) {
        this.setHorizontalNavActive(section);
        $(sectionId).down('.step-title').click();
    };
}

/* Icube Custom - update horizontal nav */ 
Checkout.prototype.gotoSection = function (section, reloadProgressBlock) {
    // Adds class so that the page can be styled to only show the "Checkout Method" step
    if ((this.currentStep == 'login' || this.currentStep == 'billing') && section == 'billing') {
        $j('body').addClass('opc-has-progressed-from-login');
    }

    if (reloadProgressBlock) {
        this.reloadProgressBlock(this.currentStep);
    }
    this.currentStep = section;
    var sectionElement = $('opc-' + section);
    sectionElement.addClassName('allow');

    /* Icube Custom - remove loader on shipping */
    if (isSkipBilling) {
        if (section == 'shipping') {
            /* Icube custom - hide loading blocker */
            jQuery('#opc-shipping .opc-content-wrapper').removeClass('loading');
        };
    }

    /* Icube Custom - save shipping method automatically if skip shipping method */
    if (isSkipShippingMethod) {
        if (section == 'shipping_method') {
            shippingMethod.save();
        };
    };
    
    /* Icube custom - Update payment content */
    if (section == 'payment') {
        this.updatePaymentContent();
    };

    /* Icube Custom - open next section proccess and scroll to top */
    if (isSkipShippingMethod ) {
        if (section != 'shipping_method') {
            this.accordion.openSection('opc-' + section);
            
            /* Icube modify default one: Scroll viewport to top of checkout steps */
            $j('html,body').animate({scrollTop: $j('#horizontal-opc-nav').offset().top}, 800);
        };
    }else{
        this.accordion.openSection('opc-' + section);

        /* Icube modify default one: Scroll viewport to top of checkout steps */
        $j('html,body').animate({scrollTop: $j('#horizontal-opc-nav').offset().top}, 800);
    }


    /* Icube custom - update horizontal nav */
    this.setHorizontalNavActive(section);

    if (!reloadProgressBlock) {
        this.resetPreviousSteps();
    }
}

// New fn: updatePaymentContent()
// Description: 
// - Update some parts of payment content
Checkout.prototype.updatePaymentContent = function(){
    var reloadTotals = new Ajax.Request(
        window.location.origin+'/customorder/checkout/reloadTotalsAjax/',
        {
            method:'post',
            onSuccess: function(transport) {
                var response = JSON.parse(transport.responseText);

                var totalsShipping = $$('table.totals')[0];
                var totalsPayment = $$('table.totals')[1];
                var newTotals = response.totals;
                if(newTotals != null){
                    totalsShipping.replace(newTotals);
                    totalsPayment.replace(newTotals);
                }
            }
        }
        );

    var loadSelectedShippingAddress = new Ajax.Request(
        window.location.origin+'/checkout/onepage/progress',
        {
            method:'get',
            parameters: { prevStep:'shipping',test:'test' },
            onCreate: function() {
                jQuery('#payment-shipToAddress').addClass('loading');
            },
            onSuccess: function(transport) {
                // response = jQuery.parseHTML(transport.responseText);
                // response = jQuery.parseHTML(response.);
                var response = transport.responseText.split('<address>');
                response = response[1].split('</address>');
                response = response[0].split('<br/>');

                if (response) {
                    var address = '<address>';

                    jQuery.each(response, function(i, value) {
                        if (i==0) {
                            address += '<strong>'+value+'</strong>';
                        }else{
                            address += value;
                        }

                        if (i+1 != response.length) {
                            address += '<br/>';
                        };
                    });

                    address += '</address>';
                }else{
                    var address = 'No address found...';
                }

                jQuery('#payment-shipToAddress').html(address);
            },
            onComplete: function() {
                jQuery('#payment-shipToAddress').removeClass('loading');
            }
        }
    );
}

// Override fn: Payment.initWhatIsCvvListeners()
// Description: 
// - Disable default one
// - Remove default one, custom tooltips using Tooltipster on hover
Payment.prototype.initWhatIsCvvListeners = function(){
        jQuery('.cvv-what-is-this').tooltipster({
            content: jQuery('#payment-tool-tip')
        });
    }

// Override fn: Checkout.back()
// Description: 
// - if previous section is hidden then do back again
Checkout.prototype.back = function(){
        if (this.loadWaiting) return;
        //Navigate back to the previous available step
        var stepIndex = this.steps.indexOf(this.currentStep);
        var section = this.steps[--stepIndex];
        var sectionElement = $('opc-' + section);

        //Traverse back to find the available section. Ex Virtual product does not have shipping section
        while ((sectionElement === null || sectionElement.hasClassName('skip')) && stepIndex > 0) {
            --stepIndex;
            section = this.steps[stepIndex];
            sectionElement = $('opc-' + section);
            console.log('skip: ' +section);
        }
            console.log('open now: ' +section);
        this.changeSection('opc-' + section);
    }