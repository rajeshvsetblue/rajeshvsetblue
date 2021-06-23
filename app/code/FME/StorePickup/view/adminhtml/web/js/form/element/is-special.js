define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectOption: function (id) {
            
        setTimeout(
        function() 
        {
            if ($("#"+id).attr('name')=='monday_status') { 
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="monday_open_time"]').hide();
                $('div[data-index="monday_close_time"]').hide();
                $('div[data-index="monday_break_time"]').hide();
                $('div[data-index="monday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="monday_open_time"]').show();
                $('div[data-index="monday_close_time"]').show();
                $('div[data-index="monday_break_time"]').show();
                $('div[data-index="monday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='tuesday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="tuesday_open_time"]').hide();
                $('div[data-index="tuesday_close_time"]').hide();
                $('div[data-index="tuesday_break_time"]').hide();
                $('div[data-index="tuesday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="tuesday_open_time"]').show();
                $('div[data-index="tuesday_close_time"]').show();
                $('div[data-index="tuesday_break_time"]').show();
                $('div[data-index="tuesday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='wednesday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="wednesday_open_time"]').hide();
                $('div[data-index="wednesday_close_time"]').hide();
                $('div[data-index="wednesday_break_time"]').hide();
                $('div[data-index="wednesday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="wednesday_open_time"]').show();
                $('div[data-index="wednesday_close_time"]').show();
                $('div[data-index="wednesday_break_time"]').show();
                $('div[data-index="wednesday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='thursday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="thursday_open_time"]').hide();
                $('div[data-index="thursday_close_time"]').hide();
                $('div[data-index="thursday_break_time"]').hide();
                $('div[data-index="thursday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="thursday_open_time"]').show();
                $('div[data-index="thursday_close_time"]').show();
                $('div[data-index="thursday_break_time"]').show();
                $('div[data-index="thursday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='friday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="friday_open_time"]').hide();
                $('div[data-index="friday_close_time"]').hide();
                $('div[data-index="friday_break_time"]').hide();
                $('div[data-index="friday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="friday_open_time"]').show();
                $('div[data-index="friday_close_time"]').show();
                $('div[data-index="friday_break_time"]').show();
                $('div[data-index="friday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='saturday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="saturday_open_time"]').hide();
                $('div[data-index="saturday_close_time"]').hide();
                $('div[data-index="saturday_break_time"]').hide();
                $('div[data-index="saturday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="saturday_open_time"]').show();
                $('div[data-index="saturday_close_time"]').show();
                $('div[data-index="saturday_break_time"]').show();
                $('div[data-index="saturday_offbreak_time"]').show();
            }} else if ($("#"+id).attr('name')=='sunday_status') {
            if (($("#"+id).val() == 0) || ($("#"+id).val() == undefined)) {
                $('div[data-index="sunday_open_time"]').hide();
                $('div[data-index="sunday_close_time"]').hide();
                $('div[data-index="sunday_break_time"]').hide();
                $('div[data-index="sunday_offbreak_time"]').hide();
            } else if ($("#"+id).val() == 1) {
                $('div[data-index="sunday_open_time"]').show();
                $('div[data-index="sunday_close_time"]').show();
                $('div[data-index="sunday_break_time"]').show();
                $('div[data-index="sunday_offbreak_time"]').show();
            }}
        
        }, 800);
    }
        
        
        

    });
   
});

