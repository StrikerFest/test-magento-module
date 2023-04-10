<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\Faq\Model\ResourceModel\Blog;
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct()
    {
        $this->_init('Tigren\HelloWorld\Model\Blog',
            'Tigren\HelloWorld\Model\ResourceModel\Blog');
    }
}
