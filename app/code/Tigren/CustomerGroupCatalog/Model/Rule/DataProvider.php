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
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if(isset($this->loadedData)){
            return $this->loadedData;
        }

        $rule = $this->getCurrentRule();
        $this->loadedData[$rule->getId()] = $rule->getData();
        return $this->loadedData;
    }

    private function getCurrentRule(): Rule
    {

        $ruleId = $this->getRuleId();
        $rule = $this->ruleFactory->create();
        if(!$ruleId){
            return $rule;
        }

        $this->resource->load($rule, $ruleId);

        return $rule;
    }

    private function getRuleId(): int
    {
//        return (int) $this->request->getParam($this->getRequestFieldName());
        return (int) $this->request->getParam('rule_id');
    }
}
