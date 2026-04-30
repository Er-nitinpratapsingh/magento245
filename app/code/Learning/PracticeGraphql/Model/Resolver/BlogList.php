<?php

namespace Learning\PracticeGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class BlogList implements ResolverInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
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

        // Create collection
        $collection = $this->collectionFactory->create();

        // print_r('hello'); die;
        $blogs = [];

        $blogs = $collection->getData();

        // foreach ($collection as $blog) {
        //     $blogs[] = [
        //         'post_id'     => $blog->getId(),
        //         'name'        => $blog->getName(),
        //         'url_key'     => $blog->getUrlKey(),
        //         'description' => $blog->getDescription(),
        //     ];
        // }

        return $blogs;
    }
}
