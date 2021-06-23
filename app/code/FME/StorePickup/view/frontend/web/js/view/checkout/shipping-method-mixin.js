define(
    [
        'FME_StorePickup/js/model/instore-pickup',
        'FME_StorePickup/js/model/instore-selected',
        'Magento_Checkout/js/model/quote',
        'mage/utils/wrapper',
        'jquery'
    ],
    function (
        inStorePickup,
        inStoreSelected,
        quote,
        wrapper,
        $
    ) {
        'use strict';
        var sameAsBillingBlock = '.shipping-address-same-as-billing-block',
            sameAsBillingCheckBoxId = '#shipping-address-same-as-billing';

        quote.shippingMethod.subscribe( function (value) {
            var storeData = inStorePickup.getInStoreData();
            if (value.carrier_code && value.carrier_code == inStorePickup.getInStoreCode()) {

                  
                  $('#fmestorepickup').show();
                 
            } else {
                 
                   $('#fmestorepickup').hide();
            }
        });
        
        var mixin = function (selectShippingMethod) {
 
            return wrapper.wrap(selectShippingMethod, function (originalAction) {
                if (selectShippingMethod.carrier_code == inStorePickup.getInStoreCode()) {
                           currentStore = $('#storepickup_id').val();

                                  $('#fmestorepickup').show();
                            } else {

                                   $('#fmestorepickup').hide();
                            }
                return originalAction(); 
            });
        };
        
        return function (target) { 
            return target.extend(mixin);
        };

    }
);
