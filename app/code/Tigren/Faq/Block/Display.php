<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\Faq\Block;
class Display extends \Magento\Framework\View\Element\Template
{
    public function
    __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }
    public function sayFaq()
    {
        return __('Slam my meat');
    }
}
