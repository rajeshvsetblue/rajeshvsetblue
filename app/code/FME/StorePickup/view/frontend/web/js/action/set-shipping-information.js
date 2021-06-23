define(
    [
        'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
    ],
    function (
        $, wrapper, quote
    ) {
        'use strict';

return function (setShippingInformationAction) {
 
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                alert("sdfdsfds");
                shippingAddress['extension_attributes'] = {
                    fmestorepickup_id: $('#fmestorepickup_id').val(),
                    fmepickup_datetime: $('#fmepickup_datetime').val()
                };
            }
            // you can write here your code according to your requirement
            return originalAction(); // it is returning the flow to original action
        });
    };
       
    }
);