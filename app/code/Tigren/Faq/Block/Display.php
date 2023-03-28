<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Block;
class Display extends \Magento\Framework\View\Element\Template
{
//    Test class - bỏ qua
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
