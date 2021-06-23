define(
    [
        'ko',
        'jquery'
    ],
    function (
        ko,
        $
    ) {
        'use strict';
        var inStoreCode         = window.checkoutConfig.fmeStorePickUp;
        var inStorePickupTime   = window.checkoutConfig.fmeStorePickUpPickupTime;
        var stores              = window.checkoutConfig.allStorePickUpLocations;
        var inStoreData         = window.checkoutConfig.storePickUpList;
        var customerData        = window.checkoutConfig.customerData;
        var firstone        = window.checkoutConfig.getfirstone;
      
        return {
            getInStoreCode: function () {
                return inStoreCode;
            },
            getInStorePickupTime: function () {
                return inStorePickupTime;
            },
            gefirstone: function () {
                return firstone;
            },
            getStores: function () {
                return stores;
            },
            getInStoreData: function () {
                return inStoreData;
            },
            getSelectedInStoreData: function (sotreData) {
                var storeAddress = {
                    available: false,
                    data: null
                };
               
               
                    storeAddress.available = true;
                    storeAddress.data = sotreData;
               
                return storeAddress;
            },
            getCustomerFirstname: function () {
                return customerData != undefined && customerData.firstname != undefined
                    ? customerData.firstname : "in-Store";
            },
            getCustomerLastname: function () {
                return customerData != undefined && customerData.lastname != undefined
                    ? customerData.lastname : "Pickup";
            }
        }
    }
);