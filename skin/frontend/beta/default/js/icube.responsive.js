// Override default Magento js breakpoints defined here: skin/frontend/rwd/default/js/app.js

var _mqSupport = false;     // kill responsive for now
if (_mqSupport) {
    var bp = {
        xsmall: 479,
        small: 639,
        medium: 767,
        large: 1023,
        xlarge: 1179
    }
}else{
    var bp = {
        xsmall: 0,
        small: 0,
        medium: 0,
        large: 0,
        xlarge: 1023
    }
}

/*
 Javascript Responsive class. Collection of general properties/methods for use with Responsive sites.
 Important: Use jQuery instead of $.  To do: Find solution for using standard jquery $.
 */

function Responsive() {

    // Init breakpoint pixel values (keep consistent with css).
    this.desktopWidth = bp.xlarge + 1;
    this.desktopSmallWidth = bp.xlarge;
    this.tabletWidth = bp.large;
    this.tabletLandscapeWidth = 1024;
    this.tabletSmallWidth = bp.medium;
    this.mobileWidth = bp.small;
    this.phoneWidth = bp.xsmall;

    // Core breakpoints (keep consistent with css).
    this.mqDesktop = 'handheld, screen and (min-width: ' + (this.desktopWidth + 1) + 'px)';
    this.mqDesktopSmall = 'handheld, screen and (max-width: ' + (this.desktopWidth) + 'px)';
    this.mqTablet = 'handheld, screen and (max-width: ' + this.tabletWidth + 'px)';
    this.mqTabletSmall = 'handheld, screen and (max-width: ' + this.tabletSmallWidth + 'px)';
    this.mqMobile = 'handheld, screen and (max-width: ' + this.mobileWidth + 'px)';
    this.mqPhone = 'handheld, screen and (max-width: ' + this.phoneWidth + 'px)';

    // Additional breakpoints (use with care).
    this.mqIsDesktop = 'handheld, screen and (min-width: ' + parseInt(this.tabletWidth + 1) + 'px)';
}