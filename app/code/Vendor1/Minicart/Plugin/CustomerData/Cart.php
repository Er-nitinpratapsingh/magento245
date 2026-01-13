<?php

namespace Vendor1\MiniCart\Plugin\CustomerData;

use Magento\Checkout\CustomerData\Cart as Subject;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Quote\Model\Quote;

class Cart
{
    /**
     * @var CheckoutCart
     */
    private $cart;

    public function __construct(
        CheckoutCart $cart
    ) {
        $this->cart = $cart;
    }

    public function afterGetSectionData(Subject $subject, array $result)
    {
        /** @var Quote $quote */
        $quote = $this->cart->getQuote();

        // Custom subtotal values - replace with your dynamic logic if needed
        $customSubtotal1 = 15.50;
        $customSubtotal2 = 25.75;

        // Example: simple estimated shipping based on quote shipping amount
        $shippingAmount = (float)$quote->getShippingAddress()->getShippingAmount();

        // If you calculate estimate via custom logic / table rates / API, put it here
        $result['estimated_shipping'] = $shippingAmount;
        // Custom subtotals added here
        $result['customSubtotal1'] = $customSubtotal1;
        $result['customSubtotal2'] = $customSubtotal2;

        return $result;
    }
}
