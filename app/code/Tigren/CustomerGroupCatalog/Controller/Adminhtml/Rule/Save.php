<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule as RuleResource;
use Tigren\CustomerGroupCatalog\Model\RuleStoreFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleStore as RuleStoreResource;
use Tigren\CustomerGroupCatalog\Model\RuleCustomerGroupFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleCustomerGroup as RuleCustomerGroupResource;
class Save extends Action implements HttpPostActionInterface
{

    public function __construct(
        Context $context,
        private RuleFactory $RuleFactory,
        private RuleResource $resource,
        private RuleStoreFactory $RuleStoreFactory,
        private RuleStoreResource $ruleStoreresource,
        private RuleCustomerGroupFactory $RuleCustomerGroupFactory,
        private RuleCustomerGroupResource $ruleCustomerGroupresource,
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->RuleFactory->create();
            $modelRuleStore = $this->RuleStoreFactory->create();
            $modelRuleCustomerGroup = $this->RuleCustomerGroupFactory->create();
            // Nếu id chưa được đặt - Id = null
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

            unset($dataRuleStore['name']);
            unset($dataRuleStore['discountAmount']);
            unset($dataRuleStore['startTime']);
            unset($dataRuleStore['endTime']);
            unset($dataRuleStore['priority']);
            unset($dataRuleStore['active']);
            unset($dataRuleStore['customer_group_ids']);
            unset($dataRuleStore['customer_group_ids-prepared-for-send']);
            unset($dataRuleStore['store-prepared-for-send']);

            unset($dataRuleCustomerGroup['name']);
            unset($dataRuleCustomerGroup['discountAmount']);
            unset($dataRuleCustomerGroup['startTime']);
            unset($dataRuleCustomerGroup['endTime']);
            unset($dataRuleCustomerGroup['priority']);
            unset($dataRuleCustomerGroup['active']);
            unset($dataRuleCustomerGroup['store-prepared-for-send']);
            unset($dataRuleCustomerGroup['store']);
            unset($dataRuleCustomerGroup['customer_group_ids-prepared-for-send']);

            dd($data);

            $model->setData($dataRule);

            try {
                $this->resource->save($model);
                $ruleId = $model->getId();
                foreach($dataRuleCustomerGroup['customer_group_ids'] as $RCG){
                    $saveData = ['rule_customerGroup_id' => null];
                    $saveData += ['rule_id' => $ruleId];
                    $saveData += ['customerGroup_id' => $RCG];
//                    print('<PRE>' . print_r($saveData) . '</PRE>');
                    $modelRuleCustomerGroup->setData($saveData);
                    $this->ruleCustomerGroupresource->save($modelRuleCustomerGroup);
                }

                foreach($dataRuleStore['store'] as $RCG){
                    $saveData = ['rule_store_id' => null];
                    $saveData += ['rule_id' => $ruleId];
                    $saveData += ['store_id' => $RCG];
//                    print('<PRE>' . print_r($saveData) . '</PRE>');
                    $modelRuleStore->setData($saveData);
                    $this->ruleStoreresource->save($modelRuleStore);
                }

            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
                die("LocalizedException");
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__("IT DIED saving rule"));
                die("Throwable");
            }



            $this->messageManager->addSuccessMessage(__("rule saved"));
            return $resultRedirect->setPath('*/*/');

        }


        return $resultRedirect->setPath('*/*/');
    }
}
