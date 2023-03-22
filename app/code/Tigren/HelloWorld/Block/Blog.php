<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Block;
class Blog extends \Magento\Framework\View\Element\Template
{
    protected $_blogFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tigren\HelloWorld\Model\BlogFactory $blogFactory
    ){
        $this->_blogFactory = $blogFactory;
        parent::_construct($context);
    }
    public function _prepareLayout()
    {
        $blog = $this->_blogFactory->create();
        $collection = $blog->getCollection();
        foreach($collection as $item){
            var_dump($item->getData());
        }
        exit;
    }
}
