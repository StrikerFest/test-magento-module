<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Observer;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;


class CustomPrice implements ObserverInterface
{

    protected $cartHelper;

    public function __construct(
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleStoreFactory $ruleStoreFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleCustomerGroupFactory $ruleCustomerGroupFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleProductFactory $ruleProductFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\Registry $registry,
        CatalogSession $catalogSession,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->cartHelper = $cartHelper;
        $this->_ruleFactory = $ruleFactory;
        $this->_ruleStoreFactory = $ruleStoreFactory;
        $this->_ruleCustomerGroupFactory = $ruleCustomerGroupFactory;
        $this->_ruleProductFactory = $ruleProductFactory;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_registry = $registry;
        $this->catalogSession = $catalogSession;
        $this->categoryRepository = $categoryRepository;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        //get the item just added to cart
        $item = $observer->getEvent()->getData('quote_item');
        $product = $observer->getEvent()->getData('product');
        //(optional) get the parent item, if exists
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);

        $itemsToPrice = array();
        $cartItems = $this->cartHelper->getQuote()->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $itemsToPrice[$item->getId()] = $item->getProduct()->getPrice();
        }


        $ruleFactory = $this->_ruleFactory->create();
        $ruleStoreFactory = $this->_ruleStoreFactory->create();
        $ruleCustomerGroupFactory = $this->_ruleCustomerGroupFactory->create();
        $ruleProductFactory = $this->_ruleProductFactory->create();

        $collection = $ruleFactory->getCollection();
        $collectionStore = $ruleStoreFactory->getCollection();
        $collectionCustomerGroup = $ruleCustomerGroupFactory->getCollection();
        $collectionProduct = $ruleProductFactory->getCollection();

        $dataStore = $collectionStore->getData();
        $dataCustomerGroup = $collectionCustomerGroup->getData();
        $dataProduct = $collectionProduct->getData();
        $customer = $this->_customerSession->create();

        if ($customer->isLoggedIn()) {

            $currentCustomerGroup = $customer->getCustomer()->getGroupId();
            $currentStore = $this->_storeManager->getStore()->getId();
            $currentProduct = $product->getId();
            $ruleId = array();

            foreach ($dataStore as $DS) {
                if ($DS['store_id'] == $currentStore || $DS['store_id'] == 0) {
                    foreach ($dataCustomerGroup as $DCG) {
                        if ($DCG['customerGroup_id'] == $currentCustomerGroup) {
                            foreach ($dataProduct as $DP) {
                                if ($DP['product_id'] == $currentProduct) {
                                    array_push($ruleId, $DP['rule_id']);
                                }
                            }
                        }
                    }
                }
            }

            $ruleId = array_unique($ruleId);

            $collection->addFieldToFilter('rule_id', array('in' => $ruleId)
            )->getSelect()->where('active = 1')->order("priority DESC");

            $data = $collection->getFirstItem()->getData('discountAmount');
            $discountAmount = (int)$data ?? 99;
            $discountAmount = 1 - $discountAmount / 100;

            $price = $itemsToPrice[$item->getId()] * $discountAmount;
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
        }
    }
}
