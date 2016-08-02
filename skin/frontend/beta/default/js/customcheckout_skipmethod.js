jQuery(document).ready(function(){
    jQuery('#opc-shipping_method').addClass('skip');
	shippingMethod.beforeSkipMethod();
});

/* Icube Custom - skip shipping method step */
ShippingMethod.prototype.beforeSkipMethod = function(){
		document.getElementById('checkout-step-shipping_method').style.display = 'none';
        document.getElementById('opc-shipping_method').style.display = 'none';
		jQuery('#horizontal-opc-nav-shipping_method').addClass('hidden');
	    jQuery('#shipping_method-progress-opcheckout').hide();
    }
   
/* Icube Custom - Don't show loader if next step is shipping_method */ 
Shipping.prototype.resetLoadWaiting = function(transport){
    if (transport && transport.responseText){
        try{
            response = eval('(' + transport.responseText + ')');
        }
        catch (e) {
            response = {};
        }
    }

    if (response.goto_section != 'shipping_method') {
        checkout.setLoadWaiting(false);
    };
}

/* Icube Custom - Disable checking whether loader exists or not */ 
ShippingMethod.prototype.save = function(){

    // if (checkout.loadWaiting!=false) return;
    if (this.validate()) {
        // checkout.setLoadWaiting('shipping-method');
        var request = new Ajax.Request(
            this.saveUrl,
            {
                method:'post',
                onComplete: this.onComplete,
                onSuccess: this.onSave,
                onFailure: checkout.ajaxFailure.bind(checkout),
                parameters: Form.serialize(this.form)
            }
        );
    }
}