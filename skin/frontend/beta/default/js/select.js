//custom select
jQuery(document).ready(function($){
	if(jQuery('select').length > 0) {
		jQuery('select').each(function(){
			var _this = this;

			// change native selectbox on start up
			changeSelect(jQuery(this));
			
			// // refresh custom selectbox on every event
			// jQuery(this).bind(getAllEvents(jQuery(this)[0]), function(e) {
			// 	changeSelect(jQuery(this));
			// });

			// refresh custom selectbox realtime
			setInterval(function(){ 
				changeSelect(jQuery(_this));
			}, 100);


			// bind selectbox text to custom selectbox on native selectbox change
			jQuery(this).change(function(){
				jQuery(this).prev().find('span').text(jQuery(this).children("option").filter(":selected").text());
			});
		});
	}
});

function changeSelect(elm){
		if (elm.css('display')!='none') {
			var width = elm.outerWidth();
			var height = elm.outerHeight();

			if(!elm.parents('.select').hasClass('select') && !elm.hasClass('no-display')){
				elm.wrap('<div class="select" />');
				
				var text = elm.children("option").filter(":selected").text();
				elm.parent().prepend('<span class="select-bg" style="width:'+width+'px;height:'+height+'px;line-height:'+height+'px"><span class="select-text">' + text + '</span></span>');
			}else{
				var text = elm.children("option").filter(":selected").text();
				elm.parent().find('.select-bg').text(text).show();
			}
		}else{
			elm.parent().find('.select-bg').hide();
		}
}

function getAllEvents(element) {
    var result = [];
    for (var key in element) {
        if (key.indexOf('on') === 0) {
            result.push(key.slice(2));
        }
    }

    return result.join(' ');
}

