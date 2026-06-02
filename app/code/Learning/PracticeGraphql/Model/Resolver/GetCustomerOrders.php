<?php

namespace Learning\PracticeGraphql\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Authorization\Model\UserContextInterface;

class GetCustomerOrders implements ResolverInterface
{
    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var UserContextInterface
     */
    protected $userContext;

    public function __construct(
        CollectionFactory $orderCollectionFactory,
        UserContextInterface $userContext
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->userContext = $userContext;
    }

    /**
     * Resolve function
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // die('hello');

        $customerId = $this->userContext->getUserId();

        if (!$customerId) {
            throw new LocalizedException(__('Customer not authenticated.'));
        }

        $orders = $this->orderCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('entity_id', 'DESC');

        $result = [];

        foreach ($orders as $order) {

            $itemsData = [];

            foreach ($order->getAllVisibleItems() as $item) {

                $itemsData[] = [
                    'name'  => $item->getName(),
                    'sku'   => $item->getSku(),
                    'qty'   => $item->getQtyOrdered(),
                    'price' => $item->getPrice()
                ];
            }

            $result[] = [
                'increment_id' => $order->getIncrementId(),
                'grand_total'  => $order->getGrandTotal(),
                'items'        => $itemsData
            ];
        }

        return $result;
    }
}