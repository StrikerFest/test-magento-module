<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule as RuleResource;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Delete constructor.
     * @param Context $context
     * @param RuleResource $resource
     * @param RuleFactory $ruleFactory
     */
    public function __construct(
        Context $context,
        private RuleResource $resource,
        private RuleFactory $ruleFactory
    ) {
        parent::__construct($context);
        $this->ruleFactory = $this->ruleFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $ruleId = (int) $this->getRequest()->getParam('rule_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$ruleId) {
            $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete'));
            return $resultRedirect->setPath('*/*/');
        }

        $model = $this->ruleFactory->create();

        try {
            $this->resource->load($model, $ruleId);
            $this->resource->delete($model);

            $this->messageManager->addSuccessMessage(__('The rule has been deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
