document.observe("dom:loaded", function() {
	$('payer_postcode').insert({ after: "<p>5 maximum characters</p>" });
	$('payer_npwp').insert({ after: "<p>15 maximum characters</p>" });
	$('payer_address1').insert({ after: "<p>35 maximum characters</p>" });
	$('payer_address2').insert({ after: "<p>35 maximum characters</p>" });
	$('payer_address3').insert({ after: "<p>35 maximum characters</p>" });
	$('order-shipping_address_street0').addClassName('validate-length maximum-length-35 minimum-length-1');
	$('order-billing_address_street0').addClassName('validate-length maximum-length-35 minimum-length-1');
	$('order-shipping_address_street1').addClassName('validate-length maximum-length-35 minimum-length-1');
	$('order-billing_address_street1').addClassName('validate-length maximum-length-35 minimum-length-1');
});

function updateDeliveryPickup() {
	
	var elmArray = {};
	var params;
	
	$$('.delivery_pickup').each(function (elem) {

		var idElem = elem.readAttribute('name');
		var idEl = idElem.split("-");
		var elemStore = document.getElementsByName("item_store_code-"+idEl[1]);
		var elemStoreSplit = elemStore[0].value.split("-");

		elmArray[idEl[1]] = {delivery:elem.value, store:elemStoreSplit[0], company:elemStoreSplit[1],pickuplocation:elemStoreSplit[2]};
	});

	params = Object.toJSON(elmArray);

	new Ajax.Request(window.location.origin+'/admin/orderitem/updateDeliveryPickupStoreAjax', {
            parameters: {data:params},
            method     : 'post',
            onComplete : function(transport) {
                }
            });
	
}