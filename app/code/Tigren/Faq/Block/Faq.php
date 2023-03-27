<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\Faq\Block;

use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Faq extends \Magento\Framework\View\Element\Template
{
    protected $_product = null;
    protected $_coreRegistry = null;
    protected $priceCurrency;
    protected $_faqFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Tigren\Faq\Model\FaqFactory $faqFactory,
        PriceCurrencyInterface $priceCurrency,
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_faqFactory = $faqFactory;
        $this->_coreRegistry = $registry;
        parent::_construct($context);
    }

    public function _prepareLayout()
    {
        //        $faqFactory = $this->_faqFactory->create();
        //        $collection = $faqFactory->getCollection();
        //        foreach($collection as $item){
        //            var_dump($item->getData());
        //        }
        //            exit;
    }

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function getAction()
    {
//        dd($this?->getRequest()->getParam('question'));
//        return $this->getUrl('faq/faq/save',['_secure' => true]);
        return 'faq/faq/save';
    }

    public function getFaqData(array $excludeAttr = [])
    {
        $data = null;
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        $faqFactory = $this->_faqFactory->create();
        $collection = $faqFactory->getCollection();

//        dd($collection->getData());
        $data = $collection->getData();

        return $data;
    }

    protected function isVisibleOnFrontend(
        \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute,
        array $excludeAttr
    ) {
        return ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr));
    }
}
