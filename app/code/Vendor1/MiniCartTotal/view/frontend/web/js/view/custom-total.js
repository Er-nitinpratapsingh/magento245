define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Vendor1_MiniCartTotal/custom-total'
        },

        /**
         * Get cart data
         */
        getCartData: function () {
            return customerData.get('cart')();
        },

        /**
         * Get custom total
         */
        getCustomTotal: function () {
            var cartData = this.getCartData();
            if (cartData && cartData.custom_total) {
                return cartData.custom_total;
            }
            return null;
        }
    });
});