<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule as RuleResource;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleCustomerGroup as RuleCustomerGroupResource;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleProduct as RuleProductResource;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleStore as RuleStoreResource;
use Tigren\CustomerGroupCatalog\Model\RuleCustomerGroupFactory;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;
use Tigren\CustomerGroupCatalog\Model\RuleProductFactory;
use Tigren\CustomerGroupCatalog\Model\RuleStoreFactory;

class Save extends Action implements HttpPostActionInterface
{
    protected $attributeSet;

    public function __construct(
        Context $context,
        private RuleFactory $RuleFactory,
        private RuleResource $resource,

        private RuleStoreFactory $RuleStoreFactory,
        private RuleStoreResource $ruleStoreResource,

        private RuleCustomerGroupFactory $RuleCustomerGroupFactory,
        private RuleCustomerGroupResource $ruleCustomerGroupResource,

        private RuleProductFactory $RuleProductFactory,
        private RuleProductResource $ruleProductResource,

        private ProductCollectionFactory $ProductCollection,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet
    ) {
        parent::__construct($context);
        $this->_productCollection = $ProductCollection;
        $this->attributeSet = $attributeSet;
        $this->ruleStoreResourceDelete = $this->ruleStoreResource;
        $this->ruleCustomerGroupResourceDelete = $this->ruleCustomerGroupResource;
        $this->ruleProductResourceDelete = $this->ruleProductResource;
    }

    public function execute(): ResultInterface
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->RuleFactory->create();
            $modelRuleStore = $this->RuleStoreFactory->create();
            $modelRuleStoreDelete = $this->RuleStoreFactory->create();
            $modelRuleCustomerGroup = $this->RuleCustomerGroupFactory->create();
            $modelRuleCustomerGroupDelete = $this->RuleCustomerGroupFactory->create();
            $modelRuleProduct = $this->RuleProductFactory->create();
            $modelRuleProductDelete = $this->RuleProductFactory->create();

            $productCollection = $this->_productCollection->create();

            if (empty($data['rule_id'])) {
                $data['rule_id'] = null;
            }
            $dataRule = $data;
            $dataRuleStore = $data;
            $dataRuleCustomerGroup = $data;

            unset($dataRule['customer_group_ids-prepared-for-send']);
            unset($dataRule['customer_group_ids']);
            unset($dataRule['store-prepared-for-send']);
            unset($dataRule['store']);
            unset($dataRule['rule']);

            unset($dataRuleStore['name']);
            unset($dataRuleStore['discountAmount']);
            unset($dataRuleStore['startTime']);
            unset($dataRuleStore['endTime']);
            unset($dataRuleStore['priority']);
            unset($dataRuleStore['active']);
            unset($dataRuleStore['customer_group_ids']);
            unset($dataRuleStore['customer_group_ids-prepared-for-send']);
            unset($dataRuleStore['store-prepared-for-send']);
            unset($dataRuleStore['rule']);

            unset($dataRuleCustomerGroup['name']);
            unset($dataRuleCustomerGroup['discountAmount']);
            unset($dataRuleCustomerGroup['startTime']);
            unset($dataRuleCustomerGroup['endTime']);
            unset($dataRuleCustomerGroup['priority']);
            unset($dataRuleCustomerGroup['active']);
            unset($dataRuleCustomerGroup['store-prepared-for-send']);
            unset($dataRuleCustomerGroup['store']);
            unset($dataRuleCustomerGroup['customer_group_ids-prepared-for-send']);
            unset($dataRuleCustomerGroup['rule']);

            array_shift($data['rule']['conditions']);
            $dataCondition = $data['rule']['conditions'];
            $data += ['conditions' => $dataCondition];
            unset($data['rule']);

            try {

                $model->setData($dataRule);
                $this->resource->save($model);
                $ruleId = $model->getId();

                // Load store collection -get all
                // Filter with if == ruleId
                // Delete
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
                foreach ($dataRuleCustomerGroup['customer_group_ids'] as $RCG) {
                    $saveData = ['rule_customerGroup_id' => null];
                    $saveData += ['rule_id' => $ruleId];
                    $saveData += ['customerGroup_id' => $RCG];
                    $modelRuleCustomerGroup->setData($saveData);
                    $this->ruleCustomerGroupResource->save($modelRuleCustomerGroup);
                }
                foreach ($dataRuleStore['store'] as $RCG) {
                    $saveData = ['rule_store_id' => null];
                    $saveData += ['rule_id' => $ruleId];
                    $saveData += ['store_id' => $RCG];
                    $modelRuleStore->setData($saveData);
                    $this->ruleStoreResource->save($modelRuleStore);
                }
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage($e);
            }
            foreach ($collectionProduct as $CS) {
                if ($CS['rule_id'] == $ruleId) {
                    $this->ruleProductResourceDelete->load($modelRuleProductDelete,
                        $CS['rule_product_id']);
                    $this->ruleProductResourceDelete->delete($modelRuleProductDelete);
                }
            }
            // Compare all product
            foreach ($productCollection->getData() as $product) {
                // Condition data - Give attribute type, operator, string value with comma
                foreach ($data['conditions'] as $conditionItem) {
                    switch ($conditionItem['attribute']) {
                        case 'sku' :
                            $sku = explode(", ", $conditionItem['value']);
                            foreach ($sku as $skuItem) {
                                if ($product['sku'] == $skuItem) {
                                    // echo '<br>---' . $product['entity_id'];
                                    $saveData = ['rule_product_id' => null];
                                    $saveData += ['rule_id' => $ruleId];
                                    $saveData += ['product_id' => $product['entity_id']];
                                    $modelRuleProduct->setData($saveData);
                                    $this->ruleProductResource->save($modelRuleProduct);
                                }
                            }
                            break;
                        case 'attribute_set_id':
                            if ($product['attribute_set_id'] == $conditionItem['value']) {
//                                echo '<br><br>+++' . $product['attribute_set_id'];
//                                echo '<br---' . $conditionItem['value'];
                                $saveData = ['rule_product_id' => null];
                                $saveData += ['rule_id' => $ruleId];
                                $saveData += ['product_id' => $product['entity_id']];
                                $modelRuleProduct->setData($saveData);
                                $this->ruleProductResource->save($modelRuleProduct);
                            }
                            break;
                    }
                }
            }

            $this->messageManager->addSuccessMessage(__("rule saved"));
            return $resultRedirect->setPath('*/*/');

        }


        return $resultRedirect->setPath('*/*/');
    }
}
