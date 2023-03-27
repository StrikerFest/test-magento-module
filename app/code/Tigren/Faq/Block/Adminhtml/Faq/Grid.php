<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Block\Adminhtml\Faq;
class Grid extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Tigren_Faq';
        $this->_controller = 'adminhtml_faq';
        $this->_headerText = __('Manage Faq');
        $this->_addButtonLabel = __('Add New Faq');
        parent::_construct();
    }
}
