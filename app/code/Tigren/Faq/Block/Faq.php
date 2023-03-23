<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\Faq\Block;
class Faq extends \Magento\Framework\View\Element\Template
{
    protected $_faqFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tigren\Faq\Model\FaqFactory $faqFactory
    ){
        $this->_faqFactory = $faqFactory;
        parent::_construct($context);
    }
    public function _prepareLayout()
    {
        $faqFactory = $this->_faqFactory->create();
        $collection = $faqFactory->getCollection();
        foreach($collection as $item){
            var_dump($item->getData());
        }
            exit;
    }
}
