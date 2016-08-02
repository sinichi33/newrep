/*
 Helper - Javascript Helper class based on jQuery. Collection of general javascript properties/methods (non system specific).
 Important: Use jQuery instead of $.  To do: Find solution to using standard jquery $.
 */
function Helper() {

    // Specifically for use with Magento easy ajax extension.
    // Loop through blockname/selector pairs and apply the jquery action. e.g. append/replace blockname data to container.
    this.easyAjaxUpdateContent = function(type, blocks, data, action) {

        for (var i=0; i < blocks.length; i++) {
            var blockName = blocks[i][0];
            var selector = blocks[i][1];
            var html = '';
            switch (type) {
                case 'custom':
                    html = jQuery.trim(data.custom_content_data[blockName]);
                    break;
                default:
                    html = jQuery.trim(data.action_content_data[blockName]);
            }
            jQuery(selector)[action](html);
        }
    }

    // Add html (containing js) to a page and preserve javascript (run scripts on load, add script to page). e.g. especially useful for adding XMLPackage data to a page.
    // object = object to add html to.
    // html = html string containing javascript.
    // action = jquery add function (e.g. html, append etc.)
    this.addHtmlWithJs = function ($object, html, action) {

        if (html == 'null') return;

        if (html) {

            $object[action](html);

            // For each script element in the XMLPackage data, create a script object which can be inserted into the DOM and run as eval.
            $(html).filter('script').each(function (i) {

                // Get javascript (without script tags).
                // IMPORTANT: $(this).text() in theory is a safer attribute to use. However, this does not work on IE8 or below so use $(this).html() instead.
                var script = $(this).html();
                // Remove add_windowLoad.
                script = _helper.replaceAll(script, 'window_addLoad', '');
                script = _helper.replaceAll(script, '<!--', ''); // Prevent IE syntax js error.
                script = _helper.replaceAll(script, '//-->', ''); // Prevent IE syntax js error.

                // Create script element.
                var scriptTag = document.createElement("script");
                scriptTag.type = "text/javascript";
                scriptTag.language = 'JavaScript1.2';
                scriptTag.text = script;

                // Add script element to DOM (required for form to work).
                $object[0].appendChild(scriptTag);
                // Run script (required for js to run as it does on page load).
                eval(script);
            });
        }
    }

    // Return true if cookies enabled on client browser.
    // Uses jquery.cookie.js plugin.
    this.isCookiesEnabled = function(domain) {

        var cookieName = 'isCookiesEnabled';
        var isEnabled = false;

        jQuery.cookie(cookieName, '1', { expires: 1, path: '/', domain: domain, secure: false });
        isEnabled = '1' == jQuery.cookie(cookieName);
        if (isEnabled) {
            jQuery.removeCookie(cookieName, { path: '/', domain: domain, secure: false });
        }
        return isEnabled;
    }

    // Return true if value is an option in list. i.e. check if listbox contains a value.
    this.IsValueInOptionList = function($element, value) {
        var exists = false;
        $element.find('option').each(function() {
            if (this.value == value) {
                exists = true;
            }
        });
        return exists;
    }

    // Get query string key value.
    this.getQueryString = function(key) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == key) {
                return pair[1];
            }
        }
    }

    // Popup browser window. Optional parameters (width, height, windowFeatures).
    this.Popup = function(url, title, width, height, windowFeatures) {

        if (width == undefined) {
            width = 600;
        }
        if (height == undefined) {
            height = 375;
        }
        if (windowFeatures == undefined) {
            windowFeatures = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,left=0,top=0';
        }

        var windowFeatures = windowFeatures + ',width=' + width + ',height=' + height;
        window.open(url, title, windowFeatures);
        return (true);
    }

    // Wrapper for the standard DOM getElementsByTagName function. IE errors on empty nodes so wrap in test.
    this.getElementsByTagName = function(data, name) {

        var returnValue = '';

        if (data.getElementsByTagName(name)[0].childNodes[0] != null) {
            returnValue = data.getElementsByTagName(name)[0].childNodes[0].nodeValue;
        }
        return returnValue;
    }

    this.getFileNameFromPath = function(path) {

        var filename = '';
        if (path.length > 0) {
            filename = path.replace(/^.*[\\\/]/, '');
        }
        return filename;
    }

    // Case insensitive global string replace.
    this.replaceAll = function (s, oldValue, newValue) {
        return s.replace(new RegExp(oldValue, 'gi'), newValue);
    }

    this.NaNToZero = function(n) {
        if (isNaN(n)) {
            return 0;
        }
        else {
            return n;
        }
    }

    this.scrollToElm = function(elm, treshold) {
        treshold = typeof treshold !== 'undefined' ? treshold : 0;
        jQuery('html, body').animate({
            scrollTop: elm.offset().top - treshold
        }, 700);
    }
}