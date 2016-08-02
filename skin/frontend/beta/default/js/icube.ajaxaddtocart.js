/*
    JS for Add to Cart via Ajax
*/

function addToCart(url) {
  new Ajax.Request(url, {
    method : 'post',
    onLoading : function() {
      // show laoder
      jQuery('body').addClass('loading');
    },
    onComplete : function(transport) {
      result = JSON.parse(transport.responseText);

      if (result.status == "SUCCESS") {
        // update minicart content
        jQuery('.header-minicart .links').html(result.minicart);
    
        var minicart = jQuery('.header-minicart #header-cart');
        minicart.show();
        jQuery('#minicart-success-message').text(Translator.translate('Item has been added successfully.')).show();
        _helper.scrollToElm(jQuery('#header-cart'),10);

        //reinit scrollbar
        jQuery("#header-cart #cart-sidebar").mCustomScrollbar();

        // remove laoder
        jQuery('body').removeClass('loading');
      }else{
        jQuery('body').removeClass('loading');
        jQuery.magnificPopup.open({
          items: {
            src: '<div class="white-popup">'+result.message+'</div>'
          },
          type: 'inline'
        });
      }
    }
  });
}

function addToCartPdp(url,data) {
  new Ajax.Request(url, {
    url: url,
    dataType: 'json',
    type : 'post',
    parameters: data,
    onLoading : function() {
      // show laoder
      jQuery('body').addClass('loading');
    },
    onComplete : function(transport) {
        result = JSON.parse(transport.responseText);

        if (result.status == "SUCCESS") {
          if (window==window.top){
            // update minicart content
            jQuery('.header-minicart .links').html(result.minicart);
        
            var minicart = jQuery('.header-minicart #header-cart');
            minicart.show();
            jQuery('#minicart-success-message').text(Translator.translate('Item has been added successfully.')).show();
            _helper.scrollToElm(jQuery('#header-cart'),10);

            //reinit scrollbar
            jQuery("#header-cart #cart-sidebar").mCustomScrollbar();

            // remove laoder
            jQuery('body').removeClass('loading');
          }else{
            // pdp loaded within iframe (quickview)

            // remove laoder
            jQuery('body').removeClass('loading');

            // update minicart content
            parent.jQuery('.header-minicart .links').html(result.minicart);
        
            var minicart = parent.jQuery('.header-minicart #header-cart');
            minicart.show();
            parent.jQuery('#minicart-success-message').text(Translator.translate('Item has been added successfully.')).show();
            parent.jQuery('html, body').animate({
                scrollTop: parent.jQuery('#header-cart').offset().top - 10
            }, 700);

            //reinit scrollbar
            parent.jQuery("#header-cart #cart-sidebar").mCustomScrollbar();

            // close quickview popup
            parent.jQuery.colorbox.close();
          }
        }else{
          jQuery('body').removeClass('loading');
          jQuery.magnificPopup.open({
            items: {
              src: '<div class="white-popup">'+result.message+'</div>'
            },
            type: 'inline'
          });
        }
    }
  });
}

function miniCartLinkOnClick(that,event) {
  event.preventDefault();
  event.stopPropagation();
  var minicart = jQuery(that).parent().find('#header-cart');
  if (minicart.css('display') == 'none') {
      //hide other skip-content
      jQuery('.header-container .skip-content').hide();
      
      jQuery('#minicart-success-message').hide();
      jQuery('#minicart-error-message').hide();
      minicart.fadeIn();
  }else{
      minicart.hide();
  }
}