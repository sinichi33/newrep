document.observe("dom:loaded", function() {
	var items = $('order_items_grid_table').select('.deliv-method');
	items.each(function (elem) {
		if(elem.innerHTML.indexOf('pickup') > -1){
			var row = elem.up();
			row.setStyle({
			  pointerEvents: 'none',
			});
			var cbox = row.select('.checkbox');
			cbox.each(function (box) {
				box.disabled=true;
			});
			console.log(row);
		}
	});
});