<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Model\Rule;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule\CollectionFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleStore\CollectionFactory as StoreFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\RuleCustomerGroup\CollectionFactory as CustomerGroupFactory;
use Tigren\CustomerGroupCatalog\Model\ResourceModel\Rule as RuleResource;
use Tigren\CustomerGroupCatalog\Model\RuleFactory;
use Tigren\CustomerGroupCatalog\Model\Rule;

class DataProvider extends ModifierPoolDataProvider
{
    private array $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private RuleResource $resource,
        private RuleFactory $ruleFactory,
        private StoreFactory $storeFactory,
        private CustomerGroupFactory $customerGroupFactory,
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeCollection = $storeFactory->create();
        $this->customerGroupCollection = $customerGroupFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $rule = $this->getCurrentRule();
        $this->loadedData[$rule->getId()] = $rule->getData();
        $storeTemp = [];
        $customerGroupTemp = [];

        // Load store
        foreach ($this->storeCollection->getData() as $item) {
            if ($item['rule_id'] == $rule->getId()) {
                array_push($storeTemp, $item['store_id']);
            }
        }

        // Load customerGroup
        foreach ($this->customerGroupCollection->getData() as $item) {
            if ($item['rule_id'] == $rule->getId()) {
                array_push($customerGroupTemp, $item['customerGroup_id']);
            }
        }

        // Load condition
        // Take data from rule_product_table
        // Check if the amount of data in the table with the same att_set_id equal to the amount of data in the product collection
        // If data in rule_product_table['att_sett_id'] < collection['...'], set attribute to sku
        // If equal, set attribute to attribute_set_id and value to the product's att_set_id
        // If bigger, error
        // The bellow data is temporally
        $this->loadedData[$rule->getId()] += [
            "rule" => [
                "conditions" => [
                    1 => [
                        "type" => "Magento\CatalogRule\Model\Rule\Condition\Combine",
                        "aggregator" => "all",
                        "value" => "1",
                        "new_child" => "",
                    ],
                    "1--1" => [
                        "type" => "Magento\CatalogRule\Model\Rule\Condition\Product",
                        "attribute" => "attribute_set_id",
                        "operator" => "==",
                        "value" => "16",
                    ]
                ]
            ]
        ];


        $this->loadedData[$rule->getId()] += ['store-prepared-for-send' => $storeTemp];
        $this->loadedData[$rule->getId()] += ['store' => $storeTemp];
        $this->loadedData[$rule->getId()] += ['customer_group_ids-prepared-for-send' => $customerGroupTemp];
        $this->loadedData[$rule->getId()] += ['customer_group_ids' => $customerGroupTemp];
        return $this->loadedData;
    }

    private function getCurrentRule(): Rule
    {

        $ruleId = $this->getRuleId();
        $rule = $this->ruleFactory->create();
        if (!$ruleId) {
            return $rule;
        }

        $this->resource->load($rule, $ruleId);

        return $rule;
    }

    private function getRuleId(): int
    {
        //        return (int) $this->request->getParam($this->getRequestFieldName());
        return (int)$this->request->getParam('rule_id');
    }
}
