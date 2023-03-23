<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
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
