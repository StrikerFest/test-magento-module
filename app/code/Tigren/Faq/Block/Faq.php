<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Block;

use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Tigren\Faq\Helper\Data;

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
        Data $helper
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_faqFactory = $faqFactory;
        $this->_coreRegistry = $registry;
        $this->helper = $helper;
        parent::_construct($context);
    }


    public function _prepareLayout()
    {
        //        Dùng để test hiển thị dữ liệu - Bỏ qua
        //        $faqFactory = $this->_faqFactory->create();
        //        $collection = $faqFactory->getCollection();
        //        foreach($collection as $item){
        //            var_dump($item->getData());
        //        }
        //            exit;
    }

    // Kiểm tra module có được người dùng bật hay tắt
    public function enableModule(): bool
    {
        return (boolean)$this->helper->isModuleEnabled();
    }

    // Lấy dữ liệu sản phẩm
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    // Lấy dữ liệu faq
    public function getFaqData(array $excludeAttr = [])
    {
        $data = null;
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        $faqFactory = $this->_faqFactory->create();
        $collection = $faqFactory->getCollection();

        // Sort dữ liệu lấy được theo thứ tự position
        // Hiển thị cho người dùng những câu hỏi có ưu tiên cao hơn
        $collection->getSelect()->order('position DESC');
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
