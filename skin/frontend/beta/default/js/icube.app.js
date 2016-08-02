/* 
 * Override functions in app.js
 */

 /* createZoom() of ProductMediaManager */
ProductMediaManager.createZoom = function(image) {
    // Destroy since zoom shouldn't be enabled under certain conditions
    ProductMediaManager.destroyZoom();

    if(
        // Don't use zoom on devices where touch has been used
        PointerManager.getPointer() == PointerManager.TOUCH_POINTER_TYPE
        // Don't use zoom when screen is small, or else zoom window shows outside body
        || Modernizr.mq("screen and (max-width:" + bp.medium + "px)")
    ) {
        return; // zoom not enabled
    }

    if(image.length <= 0) { //no image found
        return;
    }

    if(image[0].naturalWidth && image[0].naturalHeight) {
        var widthDiff = image[0].naturalWidth - image.width() - ProductMediaManager.IMAGE_ZOOM_THRESHOLD;
        var heightDiff = image[0].naturalHeight - image.height() - ProductMediaManager.IMAGE_ZOOM_THRESHOLD;

        if(widthDiff < 0 && heightDiff < 0) {
            //image not big enough

            image.parents('.product-image').removeClass('zoom-available');

            return;
        } else {
            image.parents('.product-image').addClass('zoom-available');
        }
    }

    image.elevateZoom({
        easing : true,
        zoomWindowWidth: 450, 
        zoomWindowHeight: 450
    });
}