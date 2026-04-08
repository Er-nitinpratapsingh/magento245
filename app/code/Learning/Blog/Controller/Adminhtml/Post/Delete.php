<?php
namespace Learning\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Learning\Blog\Model\PostFactory;

class Delete extends Action
{
    protected $postFactory;

    public function __construct(
        Action\Context $context,
        PostFactory $postFactory
    ) {
        parent::__construct($context);
        $this->postFactory = $postFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');

        if ($id) {
            try {
                $model = $this->postFactory->create()->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('Post deleted successfully'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error deleting post'));
            }
        }

        return $this->_redirect('*/*/');
    }
}