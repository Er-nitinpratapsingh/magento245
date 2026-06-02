<?php

namespace Learning\PracticeGraphql\Model\Resolver;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class GetSingleProduct implements ResolverInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $product = $this->productRepository->get($args['sku']);

        // echo '<pre>';
        // print_r($product->debug());
        // die();

        return [
            'name' => $product->getName(),
            'price' => (float) $product->getPrice(),
            'image' => $product->getImage(),
            'quantity' => $product->getExtensionAttributes()
                ->getStockItem()
                ->getQty()
        ];
    }
}


