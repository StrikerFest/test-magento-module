<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Tigren\Faq\Model\ResourceModel\Faq as FaqResource;
use Tigren\Faq\Model\FaqFactory;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Delete constructor.
     * @param Context $context
     * @param FaqResource $resource
     * @param FaqFactory $faqFactory
     */
    public function __construct(
        Context $context,
        private FaqResource $resource,
        private FaqFactory $faqFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $faqId = (int) $this->getRequest()->getParam('faq_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$faqId) {
            $this->messageManager->addErrorMessage(__('We can\'t find a faq to delete'));
            return $resultRedirect->setPath('*/*/');
        }

        $model = $this->faqFactory->create();

        try {
            $this->resource->load($model, $faqId);
            $this->resource->delete($model);

            $this->messageManager->addSuccessMessage(__('The faq has been deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
//        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect->setPath('*/*/');
//        return $resultRedirect;
    }
}
