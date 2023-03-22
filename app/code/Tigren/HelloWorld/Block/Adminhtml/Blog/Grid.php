<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\Example\Block\Adminhtml\Blog;
class Grid extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Tigren_Example';
        $this->_controller = 'adminhtml_blog';
        $this->_headerText = __('Manage Blogs');
        $this->_addButtonLabel = __('Add New Blog');
        parent::_construct();
    }
}
