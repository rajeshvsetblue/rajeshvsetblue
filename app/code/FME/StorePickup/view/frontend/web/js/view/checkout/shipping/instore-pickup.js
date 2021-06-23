define([
    'ko',
    'uiComponent',
    'FME_StorePickup/js/model/instore-pickup',
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'Magento_Checkout/js/model/full-screen-loader'
], function (
    ko,
    Component,
    inStorePickup,
    $,
    modal,
    url,
    fullScreenLoader
) {
   "use strict";
   
    return Component.extend({
       defaults: {
          template: "FME_StorePickup/checkout/shipping/instore-pickup"
       },

       inStorePickupTime: inStorePickup.getInStorePickupTime(),
       gefirstone: inStorePickup.gefirstone(),
       stores: inStorePickup.getStores(),
       inStorePickupTime: inStorePickup.getInStorePickupTime(),
       selectedStore: ko.observable(inStorePickup.getStores()[0]),

       initialize: function (){
         this._super();
            
            var disabled = window.checkoutConfig.shipping.pickup_date.disabled;
            var noday = window.checkoutConfig.shipping.pickup_date.noday;
            var hourMin = parseInt(window.checkoutConfig.shipping.pickup_date.hourMin);
            var hourMax = parseInt(window.checkoutConfig.shipping.pickup_date.hourMax);
            var format = window.checkoutConfig.shipping.pickup_date.format;
            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    var $el = $(element);
                    //initialize datetimepicker
                    if(noday) {
                        var options = {
                            minDate: 0,
                            dateFormat:format,
                            hourMin: hourMin,
                            hourMax: hourMax
                        };
                    } else {
                        var options = {
                            minDate: 0,
                            dateFormat:format,
                            hourMin: hourMin,
                            hourMax: hourMax,
                            beforeShowDay: function(date) {
                                var day = date.getDay();
                                return [true];
                                // if(disabledDay.indexOf(day) > -1) {
                                //     return [false];
                                // } else {
                                //     return [true];
                                // }
                            }
                        };
                    }

                    $el.datetimepicker(options);

                    var writable = valueAccessor();
                    if (!ko.isObservable(writable)) {
                        var propWriters = allBindingsAccessor()._ko_property_writers;
                        if (propWriters && propWriters.datetimepicker) {
                            writable = propWriters.datetimepicker;
                        } else {
                            return;
                        }
                    }
                    writable($(element).datetimepicker("getDate"));
                },
                update: function (element, valueAccessor) {
                    var widget = $(element).data("DateTimePicker");
                    //when the view model is updated, update the widget
                    if (widget) {
                        var date = ko.utils.unwrapObservable(valueAccessor());
                        widget.date(date);
                    }
                }
        };
       },

       isInStoreMethod: function (code) {
           return code === inStorePickup.getInStoreCode();
       },
       updateValue: function () {
                    var storeData = inStorePickup.getInStoreData();

                          var currentStore = $('#storepickup_id').val();
                          var storeAddress =  inStorePickup.getSelectedInStoreData(storeData[currentStore]);
                           var htm = '<div><p>'+storeAddress.data.last_name+'<br />'+storeAddress.data.street+'<br />Tel:'+storeAddress.data.telephone+'</p>'
                            $('#newcontent').html(htm);
                            $('#pickuptime').show();
            },
         updateContent: function () {
                     var currentStore = $('#storepickup_id').val();
                         var linkUrl = url.build('storepickup/index/ajax');
                         fullScreenLoader.startLoader();
                       $.ajax({
                          method: "POST",
                          url: linkUrl,
                          data: { store_id: currentStore},
                          dataType: "json"
                        })
                      .done(function( msg ) {
                           $('#pickupcontent').html(msg);
                        fullScreenLoader.stopLoader();

                        var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        buttons: false
                    };
                    var popup = modal(options, $('#pickupcontent'));
                    $("#pickupcontent").modal("openModal");                
                      });
            },
          

    });
});