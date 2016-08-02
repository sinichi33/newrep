jQuery(document).ready(function(){
    jQuery('#opc-billing').addClass('skip');
	billing.beforeSkipBilling();

    // update back-link
    var prevSection = jQuery('#opc-billing').prev();
    var nextSection = jQuery('#opc-billing').next();
    if (!prevSection.length) {
        if (nextSection.length && nextSection.hasClass('section')) {
            nextSection.find('.back-link').hide();
            nextSection.find('.back-link.back-to-cart').show();
        };
    };
});  

Checkout.prototype.setMethod = function(){
        if ($('login:guest') && $('login:guest').checked) {
            this.method = 'guest';
            var request = new Ajax.Request(
                this.saveMethodUrl,
                {method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'guest'}}
            );
            Element.hide('register-customer-password');
            /* Icube Custom - hide shipping password*/
            Element.hide('shipping-register-customer-password');
            /* End Icube Custom - hide shipping password */
            this.gotoSection('billing', true);
        }
        else if($('login:register') && ($('login:register').checked || $('login:register').type == 'hidden')) {
            this.method = 'register';
            var request = new Ajax.Request(
                this.saveMethodUrl,
                {method: 'post', onFailure: this.ajaxFailure.bind(this), parameters: {method:'register'}}
            );
            Element.show('register-customer-password');
            /* Icube Custom - show shipping password  */
            Element.show('shipping-register-customer-password');
            /* End Icube Custom - show shipping password  */
            this.gotoSection('billing', true);
        }
        else{
            alert(Translator.translate('Please choose to register or to checkout as a guest').stripTags());
            return false;
        }
        document.body.fire('login:setMethod', {method : this.method});
    }

/* Icube Custom - skip billing after login step */
Billing.prototype.beforeSkipBilling = function(){
		document.getElementById('checkout-step-billing').style.display = 'none';
        document.getElementById('opc-billing').style.display = 'none';
        jQuery('#horizontal-opc-nav-billing').addClass('hidden');
	    jQuery('#billing-progress-opcheckout').hide();
	    jQuery('#same_as_billing').hide();
	    if(jQuery('#checkoutSteps').hasClass('opc-firststep-login')){
		    return false;
	    }
	    this.skipBilling();
    }

/* Icube Custom - fill in the billing with dummy datas and save */
Billing.prototype.skipBilling = function(){
	if (jQuery("billing\\:firstname")){
        jQuery('#billing\\:use_for_shipping_no').click();
        var bil_firstname = jQuery("#billing\\:firstname").val();
        var bil_lastname = jQuery("#billing\\:lastname").val();
        var bil_street = jQuery("#billing\\:street1").val();
        var bil_city = jQuery("#billing\\:city").val();
        var bil_kec = jQuery("#billing\\:kecamatan").val();
//         var bil_kel = jQuery("#billing\\:kelurahan").val();
        var bil_zip = jQuery("#billing\\:postcode").val();
        var bil_telp = jQuery("#billing\\:telephone").val();
        var bil_region = jQuery("#billing\\:region_id").val();
        var bil_country = jQuery("#billing\\:country_id").val();
        var bil_email = jQuery("#billing\\:email").val();
        var bil_pass = jQuery("#billing\\:customer_password").val();
        var bil_conf_pass = jQuery("#billing\\:confirm_password").val();
        if(bil_firstname=="" || bil_firstname=="dummy"){
            jQuery("#billing\\:firstname").val('dummy');
        }
        if(bil_lastname=="" || bil_lastname=="dummy"){
            jQuery("#billing\\:lastname").val('dummy');
        }
        if(bil_street=="" || bil_street=="dummy"){
            jQuery("#billing\\:street1").val('dummy');
        }
        if(bil_city=="" || bil_city=="dummy"){
            jQuery("#billing\\:city").val('dummy');
        }
        if(bil_kec=="" || bil_kec=="dummy"){
            jQuery("#billing\\:kecamatan").val('dummy');
        }
/*
        if(bil_kel=="" || bil_kel=="dummy"){
            jQuery("#billing\\:kelurahan").val('dummy');
        }
*/
        if(bil_zip=="" || bil_zip=="111111"){
            jQuery("#billing\\:postcode").val('111111');
        }
        if(bil_telp=="" || bil_telp=="111111"){
            jQuery("#billing\\:telephone").val('111111');
        }
        if(bil_region=="" || bil_region=="dummy"){
            jQuery("#billing\\:region_id").append("<option value='111111'>dummy</option>");
            jQuery("#billing\\:region_id").val("111111");
        }
        if(bil_country=="" || bil_country=="dummy"){
            jQuery("#billing\\:country_id").append("<option value='dummy'>dummy</option>");
            jQuery("#billing\\:country_id").val("dummy");
        }
        if(bil_email=="" || bil_email=="email@email.com"){
            jQuery("#billing\\:email").val('email@email.com');
        }
        if(bil_pass=="" || bil_pass=="password123"){
            jQuery("#billing\\:customer_password").val('password123');
        }
        if(bil_conf_pass=="" || bil_conf_pass=="password123"){
            jQuery("#billing\\:confirm_password").val('password123');
        }
    }
    jQuery('#shipping-buttons-container .back-link').hide();
    document.getElementById('checkout-step-shipping').style.display = 'block';
    jQuery('#opc-shipping').addClass('active');
    this.save();
}

/* Icube Custom - copy shipping to billing then save shipping */   
Shipping.prototype.save = function(){
        if (checkout.loadWaiting!=false) return;
        var validator = new Validation(this.form);
        if (validator.validate()) {
            checkout.setLoadWaiting('shipping');
            /* call copy shipping to billing */
            this.copyShippingtoBilling();
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

/* Icube Custom - copy the shipping data to billing */
Shipping.prototype.copyShippingtoBilling = function(){
	var fields = jQuery("#co-shipping-form").serializeArray();
	var temp = [];
	var a = 0;
	
	jQuery.each( fields, function( i, field ) {
		var fieldName = field.name;
		
		if(fieldName.indexOf("shipping") == 0 && field.value != ''){
			var fieldNameNew = fieldName.replace("shipping", "billing");
			
			if(jQuery('input[name="'+fieldNameNew+'"]').attr('type') != 'hidden'){
				if(jQuery('input[name="'+fieldNameNew+'"]').length == 0) {
					if(jQuery('select[name="'+fieldNameNew+'"]').length >0){
						jQuery('select[name="'+fieldNameNew+'"]').find("option[value="+field.value+"]").prop("selected", true);  
					}
				} else {
					if(fieldNameNew == 'billing[street][]')
					{
						temp.push(field.value);
					}
					jQuery('input[name="'+fieldNameNew+'"]').val(field.value);
				}
			}
		}
		
	});
	
	jQuery('input[name="billing[street][]"]').each(function( i, field ) 
	{
		jQuery(this).val(temp[a]);
		a++;
	});
	
	jQuery('#billing\\:region_id').val(jQuery('#shipping\\:region_id').val());
    jQuery('#billing\\:region').val(jQuery('#shipping\\:region').val());
    jQuery('#billing-address-select').val(jQuery('#shipping-address-select').val());
	billing.saveSkipNext();
}

/* Icube Custom - save billing skip next step */
Billing.prototype.saveSkipNext = function(){
        var validator = new Validation(this.form);
        if (validator.validate()) {
            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method: 'post',
                    onComplete: function() { },
                    onSuccess: function() { },
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
            );
        }
}

