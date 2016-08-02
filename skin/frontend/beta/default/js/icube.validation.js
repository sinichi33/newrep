/* 
 * Override functions in js/prototype/validation.js
 *
 * Description:
 * - add validation failed class for custom select
 *
 *
 */

Validation.test = function(name, elm, useTitle) {
    var v = Validation.get(name);
    var prop = '__advice'+name.camelize();
    try {
    if(Validation.isVisible(elm) && !v.test($F(elm), elm)) {
        //if(!elm[prop]) {
            var advice = Validation.getAdvice(name, elm);
            if (advice == null) {
                advice = this.createAdvice(name, elm, useTitle);
            }
            this.showAdvice(elm, advice, name);
            this.updateCallback(elm, 'failed');
        //}
        elm[prop] = 1;
        if (!elm.advaiceContainer) {
            elm.removeClassName('validation-passed');
            elm.addClassName('validation-failed');

            // icube: add validation failed class to custom select
            if (elm.up().hasClassName('select')) {
                elm.up().addClassName('validation-failed');
            };
        }

       if (Validation.defaultOptions.addClassNameToContainer && Validation.defaultOptions.containerClassName != '') {
            var container = elm.up(Validation.defaultOptions.containerClassName);
            if (container && this.allowContainerClassName(elm)) {
                container.removeClassName('validation-passed');
                container.addClassName('validation-error');
            }
        }
        return false;
    } else {
        var advice = Validation.getAdvice(name, elm);
        this.hideAdvice(elm, advice);
        this.updateCallback(elm, 'passed');
        elm[prop] = '';
        elm.removeClassName('validation-failed');
        elm.addClassName('validation-passed');

        // icube: remove validation failed class to custom select
        if (elm.up().hasClassName('select')) {
            elm.up().removeClassName('validation-failed');
        };

        if (Validation.defaultOptions.addClassNameToContainer && Validation.defaultOptions.containerClassName != '') {
            var container = elm.up(Validation.defaultOptions.containerClassName);
            if (container && !container.down('.validation-failed') && this.allowContainerClassName(elm)) {
                if (!Validation.get('IsEmpty').test(elm.value) || !this.isVisible(elm)) {
                    container.addClassName('validation-passed');
                } else {
                    container.removeClassName('validation-passed');
                }
                container.removeClassName('validation-error');
            }
        }
        return true;
    }
    } catch(e) {
        throw(e)
    }
}