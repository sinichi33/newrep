/*
 Javascript Store class. Collection of general properties/methods for use with Magento store.
 Important: Use jQuery instead of $.  To do: Find solution to using standard jquery $.
 */

function Store() {

    // Returns the current page name including any extension e.g. default.aspx.
    this.getPageName = function(includePath) {

        // Optional parameter. If false, ajax call is synchronous (instead of default asynchronous).
        includePath = (typeof includePath == "undefined") ? 'false' : includePath;

        var pageName = window.location.pathname;
        if (!includePath)
        {
            pageName = pageName.substring(pageName.lastIndexOf('/') + 1);
        }
        return pageName;
    }

    // Log a Google Analytics event. To be used with async Google Analytics tracking code only i.e. NOT legacy non-async code.'
    // Value is an optional parameter. Must be an integer.
    // Call is made in live production environment only (detected by developer mode on/off. Requires jdata custom extension.
    this.googleEventTracker = function(category, action, label, value) {
        if (window.icube.jdata && !window.icube.jdata.isDeveloperMode) {
            if (typeof value == "undefined") {
                _gaq.push(['_trackEvent', category, action, label])
            }
            else {
                _gaq.push(['_trackEvent', category, action, label, value])
            }
        }
    }
}
