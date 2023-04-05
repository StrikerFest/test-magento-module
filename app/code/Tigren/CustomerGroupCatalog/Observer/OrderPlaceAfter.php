<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class OrderPlaceAfter implements ObserverInterface
{

    public function __construct(
        \Tigren\CustomerGroupCatalog\Model\HistoryFactory $historyFactory,
        \Tigren\CustomerGroupCatalog\Model\ResourceModel\History $historyResource,
        \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tigren\CustomerGroupCatalog\Model\RuleStoreFactory $ruleStoreFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleCustomerGroupFactory $ruleCustomerGroupFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleProductFactory $ruleProductFactory,
        LoggerInterface $logger

    ) {
        $this->logger = $logger;
        $this->_ruleProductFactory = $ruleProductFactory;
        $this->_ruleCustomerGroupFactory = $ruleCustomerGroupFactory;
        $this->_ruleStoreFactory = $ruleStoreFactory;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->_ruleFactory = $ruleFactory;
        $this->_historyFactory = $historyFactory;
        $this->_historyResource = $historyResource;
    }

    public function execute(Observer $observer)
    {
        // TODO: Implement execute() method.
        try {
            $historyFactory = $this->_historyFactory->create();
            $ruleFactory = $this->_ruleFactory->create();
            $ruleStoreFactory = $this->_ruleStoreFactory->create();
            $ruleCustomerGroupFactory = $this->_ruleCustomerGroupFactory->create();
            $ruleProductFactory = $this->_ruleProductFactory->create();

            $collection = $ruleFactory->getCollection();
            $collectionStore = $ruleStoreFactory->getCollection();
            $collectionCustomerGroup = $ruleCustomerGroupFactory->getCollection();
            $collectionProduct = $ruleProductFactory->getCollection();

            $dataProduct = $collectionProduct->getData();
            $dataCustomerGroup = $collectionCustomerGroup->getData();
            $dataStore = $collectionStore->getData();
            $customer = $this->_customerSession->create();
            $order = $observer->getEvent()->getOrder();
            $orderId = $order->getIncrementId();

            $currentCustomerGroup = $customer->getCustomer()->getGroupId();
            $currentCustomerId = $customer->getCustomer()->getId();
            $currentStore = $this->_storeManager->getStore()->getId();
            $ruleId = array();
            foreach ($dataStore as $DS) {
                if ($DS['store_id'] == $currentStore || $DS['store_id'] == 0) {
                    foreach ($dataCustomerGroup as $DCG) {
                        if ($DCG['customerGroup_id'] == $currentCustomerGroup) {
                            foreach ($dataProduct as $DP) {
                                foreach ($order->getAllVisibleItems() as $item) {
                                    if ($DP['product_id'] == $item->getProductId()) {
                                        array_push($ruleId, $DP['rule_id']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $ruleId = array_unique($ruleId);
            $collection->addFieldToFilter('rule_id', array(
                    'in' => $ruleId
                )
            )->getSelect()->where('active = 1')->order("priority DESC");
            $history = [];
            $history += ['rule_id' => $collection->getFirstItem()->getData('rule_id')];
            $history += ['customer_id' => $currentCustomerId];
            $history += ['order_id' => $orderId];

            $historyFactory->setData($history);
            $this->_historyResource->save($historyFactory);

        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }

    }
}
