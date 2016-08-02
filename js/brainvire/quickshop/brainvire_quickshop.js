/*
 * ICUBE modify:
 * - disable default trigger link
 * - custom getting pathname
 */

jQuery.noConflict();
jQuery(function($) {
	var myhref,qsbtt;

	function ieVersion(){
		var rv = -1; 
		if (navigator.appName == 'Microsoft Internet Explorer'){
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
				rv = parseFloat( RegExp.$1 );
		}
		return rv;
	}

	function readHref(){
		var mypath = arguments[0];
		var patt = /\/[^\/]{0,}$/ig;
		if(mypath[mypath.length-1]=="/"){
			mypath = mypath.substring(0,mypath.length-1);
			return (mypath.match(patt)+"/");
		}
		return mypath.match(patt);
	}


	function strTrim(){
		return arguments[0].replace(/^\s+|\s+$/g,"");
	}

	function _qsJnit(){
	

		
		var selectorObj = arguments[0];
		var listprod = $(selectorObj.itemClass);
		var qsImg;
		var mypath = 'quickshop/index/view';
		if(Brainvire.QuickShop.BASE_URL.indexOf('index.php') == -1){
			mypath = 'index.php/quickshop/index/view';
		}
		var baseUrl = Brainvire.QuickShop.BASE_URL + mypath;
		
		/* ICUBE: disable default trigger link */
		// var _qsHref = "<a class=\"colorbox_iframe\" href=\"#\" style=\"visibility:hidden;position:absolute;top:0;left:0\"><img  alt=\"quickshop\" src=\""+Brainvire.QuickShop.QS_IMG+"\" /></a>";
  		// $(document.body).append(_qsHref);
		
		var qsHandlerImg = $('#brainvire_quickshop_handler img');

		$.each(listprod, function(index, value) { 
			var reloadurl = baseUrl;
			
			myhref = $(value).children(selectorObj.aClass );

			/* ICUBE: custom getting pathname */
			// var prodHref = readHref(myhref.attr('href'))[0];
			var prodHref = myhref[0].pathname;
			prodHref[0] == "\/" ? prodHref = prodHref.substring(1,prodHref.length) : prodHref;
			prodHref=strTrim(prodHref);
			
			reloadurl = baseUrl+"/path/"+prodHref;	
			version = ieVersion();	
			if(version < 8.0 && version > -1){
				reloadurl = baseUrl+"/path"+prodHref;
			}
			
			$(value).find('.colorbox_iframe').attr('href',reloadurl);
			
			/* ICUBE: disable default trigger link */
			// $(selectorObj.imgClass, this).bind('mouseover', function() {
			// 	var o = $(this).offset();
			// 	$('.colorbox_iframe').attr('href',reloadurl).show()
			// 		.css({
			// 			'top': o.top+($(this).height() - qsHandlerImg.height())/2+'px',
			// 			'left': (o.left+($(this).width() - qsHandlerImg.width())/2 - 52)+'px',
			// 			'visibility': 'visible'
			// 		});
			// });

			$(value).bind('mouseout', function() {
				$('.colorbox_iframe').hide();
			});
		});

		$('.colorbox_iframe')
			.bind('mouseover', function() {
				$(this).show();
			})
			.bind('click', function() {
				$(this).hide();
			});
            $(".colorbox_iframe").colorbox({iframe:true, width:Brainvire.QuickShop.QS_FRM_WIDTH, height:Brainvire.QuickShop.QS_FRM_HEIGHT});	
	
	}

	_qsJnit({
		itemClass : '.products-grid li.item', 
		aClass : 'a.product-image', 
		imgClass: '.product-image img'
	});

});
