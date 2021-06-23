var config = {
       "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'FME_StorePickup/js/model/shipping-save-processor/default'
        }
    },
config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'FME_StorePickup/js/view/checkout/shipping-method-mixin': true
            }
            // ,
            //  'Magento_Checkout/js/model/shipping-save-processor/default': {
            //  	'FME_StorePickup/js/model/shipping-save-processor/default' : true
            //  }
        }, // this is how js mixin is defined

	}
};
