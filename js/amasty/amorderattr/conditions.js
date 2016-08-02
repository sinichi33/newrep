/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Orderattr
*/

var amOrderattrCondition = new Class.create();

amOrderattrCondition.prototype = {
    conditions: null,
    initialize: function(conditions)
    {
        this.conditions = conditions;
        
        
        
    },
    check: function(){
       if (this.conditions && this.conditions['shipping_methods']){
            var shippingForm = $$('.sp-methods')[0];
            if (shippingForm && !shippingForm.getAttribute('shipping_conditions_initialized')){
                this.initShippingMethodsCondition();
                shippingForm.setAttribute('shipping_conditions_initialized', 1);
            }
            
        } 
    },
    initShippingMethodsCondition: function(){
        var _caller = this;
        var allowed_shipping_methods = this.conditions['shipping_methods'];
        
        $$('#co-shipping-method-form input').each(function(el){
            if (el.id.indexOf('s_method_') != -1) {
                el.observe('change', _caller.onShippingMethodChange.bind(_caller, el, allowed_shipping_methods));
            }
        })
        
        var checked = $$('#co-shipping-method-form input:checked')[0];
        if (checked) {
            this.onShippingMethodChange(checked, allowed_shipping_methods);
        } else {
            this.hideOnStart(allowed_shipping_methods);
        }
        
    },
    onShippingMethodChange: function(input, shipping_methods){
        var current_shipping_method = input.id.replace('s_method_', '');
        var available = $H(shipping_methods[current_shipping_method]);
        
        for(var shipping_method in shipping_methods){
            for(var ind in shipping_methods[shipping_method]){
                var attribute = shipping_methods[shipping_method][ind];
                
                if (typeof(attribute) !== 'function'){
                    var objects = $$('[id="anchor_' + attribute + '"]');
                    
                    if (objects){
                        objects.each(function(object){
                            var row = object.up();
                            row.setStyle({
                                'display': (!available.get(attribute) ? 'none' : '')
                            })
                            
                            row.setAttribute('noshow', 1);
                            
                        })
                        
                    }
                    
                }
            }
        }
        
    },
    hideOnStart: function(shipping_methods) {
        for(var shipping_method in shipping_methods) {
            for(var ind in shipping_methods[shipping_method]) {
                var attribute = shipping_methods[shipping_method][ind];
                
                if (typeof(attribute) !== 'function'){
                    var objects = $$('[id="anchor_' + attribute + '"]');
                    
                    if (objects) {
                        objects.each(function(object) {
                            var row = object.up();
                            row.setStyle({
                                'display': 'none'
                            })
                            
                            row.setAttribute('noshow', 1);
                            
                        })
                        
                    }
                    
                }
            }
        }
    }
}