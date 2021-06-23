define([
    'Magento_Ui/js/form/element/date'
], function(Date) {
    'use strict';

    return Date.extend({
        defaults: {
            options: {
                showsDate: true,
                showsTime: true,
                timeOnly: true
            },

            elementTmpl: 'ui/form/element/date'
        }
    });
});