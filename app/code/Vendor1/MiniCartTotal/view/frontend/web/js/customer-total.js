define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'domReady!'
], function ($, customerData) {
    'use strict';

    return function () {
        var cart = customerData.get('cart');

        /**
         * Update customer total display
         */
        function updateCustomerTotal(cartData) {
            if (cartData && cartData.customer_total) {
                $('#customer-total-value').html(cartData.customer_total);
                $('#customer-total-container').show();
            } else {
                $('#customer-total-container').hide();
            }
        }

        // Initial update
        updateCustomerTotal(cart());

        // Subscribe to cart updates
        cart.subscribe(function (cartData) {
            updateCustomerTotal(cartData);
        });
    };
});