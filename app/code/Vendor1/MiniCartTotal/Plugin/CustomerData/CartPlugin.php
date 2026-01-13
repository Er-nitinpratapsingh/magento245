<?php

namespace Vendor1\MiniCartTotal\Plugin\CustomerData;

use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class CartPlugin
{
    protected $checkoutSession;
    protected $priceHelper;

    public function __construct(
        CheckoutSession $checkoutSession,
        PriceHelper $priceHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->priceHelper = $priceHelper;
    }

    /**
     * Add custom total to mini cart
     *
     * @param Cart $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(Cart $subject, $result)
    {
        $quote = $this->checkoutSession->getQuote();

        if (!$quote || !$quote->getId()) {
            return $result;
        }

        // Calculate your custom total
        // Example: Add a fixed amount or percentage
        // $subtotal = $quote->getSubtotal();
        // $customTotal = $subtotal * 0.05; // 5% of subtotal as example

        // Set fixed custom total value
        $customTotal = 50; // Fixed value of 50

        // Format the custom total
        $formattedCustomTotal = $this->priceHelper->currency($customTotal, true, false);

        // Add custom total to the result
        // if (isset($result['subtotal'])) {
        //     $result['custom_total'] = [
        //         'label' => __('Customer Total'),
        //         'value' => $customTotal,
        //         'formatted_value' => $formattedCustomTotal
        //     ];
        // }

        // Add custom total to the subtotal_incl_tax section for display
        $result['customer_total'] = $formattedCustomTotal;
        $result['customer_total_value'] = $customTotal;

        return $result;
    }
}
