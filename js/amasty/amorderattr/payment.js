/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Orderattr
*/

if (typeof(Payment)!='undefined'){
    Payment.prototype.init = function () 
    {
        if ('function' == typeof(this.beforeInit))
        {
            this.beforeInit();
        }
        var elements = Form.getElements(this.form);
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            } else {
                if (!elements[i].name.match('amorderattr'))
                {
                    elements[i].disabled = true;
                }
            }
            elements[i].setAttribute('autocomplete','off');
        }
        if (method) this.switchMethod(method);
        if ('function' == typeof(this.afterInit))
        {
            this.afterInit();
        }
    }
}

if (typeof(Review)!='undefined'){
    Review.prototype.save =function(){
            if ($('form_review'))
            {
                var formReview = $('form_review');
                var validator = new Validation(formReview);
                if (validator.validate()) {
                    if (checkout.loadWaiting!=false) return;
                    checkout.setLoadWaiting('review');
                    var params = Form.serialize(payment.form);
                    if (this.agreementsForm) {
                        params += '&'+Form.serialize(this.agreementsForm);
                    }
                    var formReviewParam = Form.serialize(formReview);
                    params += '&' + formReviewParam; 
                    params.save = true;
                    var request = new Ajax.Request(
                        this.saveUrl,
                        {
                            method:'post',
                            parameters:params,
                            onComplete: this.onComplete,
                            onSuccess: this.onSave,
                            onFailure: checkout.ajaxFailure.bind(checkout)
                        }
                    );
                 }
            }
            else{
                if (checkout.loadWaiting!=false) return;
                checkout.setLoadWaiting('review');
                var params = Form.serialize(payment.form);
                if (this.agreementsForm) {
                    params += '&'+Form.serialize(this.agreementsForm);
                }
                params.save = true;
                var request = new Ajax.Request(
                    this.saveUrl,
                    {
                        method:'post',
                        parameters:params,
                        onComplete: this.onComplete,
                        onSuccess: this.onSave,
                        onFailure: checkout.ajaxFailure.bind(checkout)
                    }
                );
            }
    }
}