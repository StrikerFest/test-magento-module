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
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleCustomerGroup as RuleCustomerGroupResource;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleProduct as RuleProductResource;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleStore as RuleStoreResource;
use Tigren\CustomerGroupCatalog\Model\RuleCustomerGroupFactory;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;
use Tigren\CustomerGroupCatalog\Model\RuleProductFactory;
use Tigren\CustomerGroupCatalog\Model\RuleStoreFactory;

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
        private RuleFactory $ruleFactory,

        private RuleStoreFactory $RuleStoreFactory,
        private RuleStoreResource $ruleStoreResource,

        private RuleCustomerGroupFactory $RuleCustomerGroupFactory,
        private RuleCustomerGroupResource $ruleCustomerGroupResource,

        private RuleProductFactory $RuleProductFactory,
        private RuleProductResource $ruleProductResource,
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
        $this->ruleStoreResourceDelete = $this->ruleStoreResource;
        $this->ruleCustomerGroupResourceDelete = $this->ruleCustomerGroupResource;
        $this->ruleProductResourceDelete = $this->ruleProductResource;
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
            // Dependency deletion
            $modelRuleStore = $this->RuleStoreFactory->create();
            $modelRuleStoreDelete = $this->RuleStoreFactory->create();
            $modelRuleCustomerGroup = $this->RuleCustomerGroupFactory->create();
            $modelRuleCustomerGroupDelete = $this->RuleCustomerGroupFactory->create();
            $modelRuleProduct = $this->RuleProductFactory->create();
            $modelRuleProductDelete = $this->RuleProductFactory->create();

            $collectionStore = $modelRuleStore->getCollection();
            $collectionCustomerGroup = $modelRuleCustomerGroup->getCollection();
            $collectionProduct = $modelRuleProduct->getCollection();

            foreach ($collectionStore as $CS) {
                if ($CS['rule_id'] == $ruleId) {
                    $this->ruleStoreResourceDelete->load($modelRuleStoreDelete, $CS['rule_store_id']);
                    $this->ruleStoreResourceDelete->delete($modelRuleStoreDelete);
                }
            }

            foreach ($collectionCustomerGroup as $CCG) {
                if ($CCG['rule_id'] == $ruleId) {
                    $this->ruleCustomerGroupResourceDelete->load($modelRuleCustomerGroupDelete,
                        $CCG['rule_customerGroup_id']);
                    $this->ruleCustomerGroupResourceDelete->delete($modelRuleCustomerGroupDelete);
                }
            }

            foreach ($collectionProduct as $CS) {
                if ($CS['rule_id'] == $ruleId) {
                    $this->ruleProductResourceDelete->load($modelRuleProductDelete,
                        $CS['rule_product_id']);
                    $this->ruleProductResourceDelete->delete($modelRuleProductDelete);
                }
            }

            $this->resource->load($model, $ruleId);
            $this->resource->delete($model);

            $this->messageManager->addSuccessMessage(__('The rule has been deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
