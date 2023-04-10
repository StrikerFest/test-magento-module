<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManager;

/**
 * Class RuleStore
 * @package Tigren\CustomerGroupCatalog\Ui\Component\Listing\Column
 * Tigren Solutions <info@tigren.com>
 */
class RuleStore extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManager $storeManager
     * @param \Tigren\CustomerGroupCatalog\Model\RuleStoreFactory $ruleStoreFactory
     * @param array $components
     * @param array $data
     */

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManager $storeManager,
        \Tigren\CustomerGroupCatalog\Model\RuleStoreFactory $ruleStoreFactory,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->_ruleStoreFactory = $ruleStoreFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {

            $ruleStoreFactory = $this->_ruleStoreFactory->create();
            $collection = $ruleStoreFactory->getCollection();

            $groups = $this->storeManager->getWebsite()->getGroups();

            foreach ($dataSource['data']['items'] as &$item) {
                $tempStoreView = "";
                $tempSVN = "";
                foreach ($collection->getData() as $storeI) {

                    if ($item['rule_id'] == $storeI['rule_id']) {
                        if($storeI['store_id'] == 0){
                            $item += ['store' => 'All store views'];
                            break;
                            //                                    return $dataSource;
                        }
                        foreach ($groups as $group) {
                            $checkStore = 0;
                            $stores = $group->getStores();
                            foreach ($stores as $store) {

                                if($storeI['store_id'] == $store->getId() )
                                {
                                    // Store temp store name
                                    if($checkStore == 0) {
                                        $tempSVN = $group->getName();
                                        $checkStore = 1;
                                    }
                                    // Store store-view name
                                    $storeViewName = $store->getName();

                                    // Combine store-view name
                                    !empty($tempSVN) ? ($tempSVN = $tempSVN . " - " . $storeViewName) : ($tempSVN = $storeViewName);
                                }
                            }
                            // End foreach - Get store view

                        }
                        // End foreach - Get store
                        $tempStoreView =  $tempStoreView . " - " . $tempSVN;
                    }
                }
                // Remove duplication store - Result of the code above
                $tempStoreView = implode(' | ', array_unique(explode(' - ', $tempStoreView)));

                $item += ['store' => $tempStoreView];
            }
        }
        return $dataSource;
    }
}
