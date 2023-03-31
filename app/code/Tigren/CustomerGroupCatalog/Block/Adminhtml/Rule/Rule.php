<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Block\Adminhtml\Rule;

use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Tigren\CustomerGroupCatalog\Helper\Data;

class Rule extends \Magento\Framework\View\Element\Template
{
    protected $_product = null;
    protected $_coreRegistry = null;
    protected $priceCurrency;
    protected $_ruleFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory,
        PriceCurrencyInterface $priceCurrency,
        Data $helper
    ) {
//        dd("BLOCk in rule rule.php");
        $this->priceCurrency = $priceCurrency;
        $this->_ruleFactory = $ruleFactory;
        $this->_coreRegistry = $registry;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function enableModule(): bool
    {
        return (boolean)$this->helper->isModuleEnabled();
    }

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function getRuleData(array $excludeAttr = [])
    {
        $data = null;
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        $ruleFactory = $this->_ruleFactory->create();
        $collection = $ruleFactory->getCollection();

//        $collection->getSelect()->order('position DESC');
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
