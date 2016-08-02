/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com)
* @package Amasty_Orderattr
*/

var amLinkedFields = new Class.create();

amLinkedFields.prototype = {
    initialize: function(parentField, childField, options, relations)
    {
        this.parentField = parentField;
        this.childField  = childField;
        this.options     = options;
        this.relations   = relations;
        if ($(this.parentField))
        {
            $(this.parentField).observe('change', this.onChange.bind(this));
        }
        this.onChange();
    },
    
    onChange: function()
    {
        $(this.childField).childElements().each(function(elem){ elem.remove(); });
        for( var i in this.options)
        {
            if (this.relations[ this.options[i].value] == $(this.parentField).value || "" ==  this.options[i].value)
            {
                optionElement = document.createElement('option');
                optionElement.value     =  this.options[i].value;
                optionElement.text      =  this.options[i].label;
                optionElement.innerText =  this.options[i].label;
                $(this.childField).appendChild(optionElement);
            }
        }
    }
};



var amLinkedFieldsAjax = new Class.create();

amLinkedFieldsAjax.prototype = {
    initialize: function(parentField, childField,url,loading,parentAttributeId)
    {
        this.parentField = parentField;
        this.childField  = childField;
        this.url         = url;
        this.loading     = loading;
        this.parentAttributeId    = parentAttributeId;
        if ($(this.parentField))
        {
            $(this.parentField).observe('change', this.onChange.bind(this));
        }
        this.onChange();
    },
    
    onChange: function()
    {
        $(this.childField).childElements().each(function(elem){ elem.remove(); });
        optionElement = document.createElement('option');
        optionElement.value     =  0;
        optionElement.text      =  this.loading;
        optionElement.innerText =  this.loading;
        $(this.childField).appendChild(optionElement);
        $(this.childField).disable(); 
        parentId  = 'parentId=' + $(this.parentField).value;
        parentAttributeId  = 'parentAttributeId='+ this.parentAttributeId;
        child   = 'childField=' + this.childField;
        var postData = parentId + '&' + child + '&' + parentAttributeId;
        new Ajax.Request(this.url, {
            method: 'post',
            postBody : postData,
            requestHeaders: {Accept: 'application/json'},
            onSuccess: function(transport) {
                $(this.childField).childElements().each(function(elem){ 
                    elem.remove(); 
                });
                if ('' != transport.responseText){ 
                    var data = transport.responseText.evalJSON(true);
                    $H(data[0]).each(function(pair) {
                        optionElement = document.createElement('option');
                        optionElement.value     =  pair.key;
                        optionElement.text      =  pair.value;
                        if ( pair.key == data[1]){
                            optionElement.selected = true;
                        }
                        optionElement.innerText =  pair.value;
                        $(this.childField).appendChild(optionElement);
                    }.bind(this));
                }
                else{
                    optionElement = document.createElement('option');
                    optionElement.value     =  '';
                    optionElement.text      =  '';
                    $(this.childField).appendChild(optionElement);
                }
                $(this.childField).enable(); 
            }.bind(this),
            onFailure: function()
            {
                alert("Something gone wrong. Please try one more time.")
            }    
        });
    }
};
