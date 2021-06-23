define(
    [
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'FME_StorePickup/js/model/instore-selected',
        'FME_StorePickup/js/model/save-instore',
        'FME_StorePickup/js/model/instore-selected-value'
    ], function (
        quote,
        urlBuilder,
        inStoreItem,
        inStoreSaveProcessor,
        inStoreSelectedValue
    ) {
        'use strict';

        return function () {
            var serviceUrl, payload;

            if (quote.getQuoteId() && inStoreItem() && inStoreSelectedValue() != null) {
                payload = {
                    quoteId: quote.getQuoteId(),
                    inStoreCode: inStoreSelectedValue()
                };

                serviceUrl = urlBuilder.createUrl('/inStore/saveInStore', {});

                return inStoreSaveProcessor(serviceUrl, payload);
            }

        }
    }
);
