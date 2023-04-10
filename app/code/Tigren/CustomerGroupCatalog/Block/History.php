<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Block;


use Tigren\CustomerGroupCatalog\Model\RuleFactory;

class History extends \Magento\Framework\View\Element\Template
{
    protected $_historyFactory;
    protected $_orderCollectionFactory;

    public function __construct(
        \Tigren\CustomerGroupCatalog\Model\HistoryFactory $historyFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        RuleFactory $ruleFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Sales\Model\OrderFactory $orderCollectionFactory

    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_historyFactory = $historyFactory;
        $this->_ruleFactory = $ruleFactory;
        $this->_customerSession = $customerSession;

        parent::__construct($context);
    }

    public function getHistory()
    {
        $orderFactory = $this->_orderCollectionFactory->create();
        $ruleFactory = $this->_ruleFactory->create();
        $historyFactory = $this->_historyFactory->create();

        $histories = $historyFactory->getCollection();
        $rules = $ruleFactory->getCollection();
        $orders = $orderFactory->getCollection();

        $histories->getSelect()->order('order_id DESC');
        $historiesCombine = $histories->getData();

        $customer = $this->_customerSession->create();
        $currentCustomerId = $customer->getCustomer()->getId();

        $result = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $httpContext = $objectManager->get('Magento\Framework\App\Http\Context');
        $isLoggedIn = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        if ($isLoggedIn) {
            $limit = sizeof($histories->getData());
            foreach ($histories as $history) {
                foreach ($orders as $orderCustomer) {
                    if ($orderCustomer->getData('customer_id') == $currentCustomerId) {
                        $ruleExtraData = [];
                        $orderExtraData = [];

                        foreach ($rules as $rule) {
                            if ($history->getData('rule_id') == $rule->getData('rule_id')) {
                                $ruleExtraData += ['rule_name' => $rule->getData('name')];
                                $ruleExtraData += ['rule_discount' => $rule->getData('discountAmount')];
                            }
                        }
                        foreach ($orders as $order) {
                            if ($history->getData('order_id') == $order->getData('increment_id')) {
                                $orderExtraData += ['order_price' => $order->getData('base_grand_total')];
                                $orderExtraData += ['order_id' => $order->getData('increment_id')];
                                $orderExtraData += ['order_currency' => $order->getData('order_currency_code')];
                                $orderExtraData += ['created_at' => $order->getData('created_at')];
                            }
                        }
                        foreach ($historiesCombine as $historyCombine) {
                            $historyCombine += $ruleExtraData;
                            $historyCombine += $orderExtraData;
                            if (sizeof($result) < $limit) {
                                array_push($result, $historyCombine);
                            }
                        }

                    }
                }
            }
            //        dd($order->getData());
            return $result;
        } else {
            return false;
        }
    }
}
