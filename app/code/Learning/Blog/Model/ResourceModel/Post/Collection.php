<?php
namespace Learning\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Learning\Blog\Model\Post::class,
            \Learning\Blog\Model\ResourceModel\Post::class
        );
    }
}