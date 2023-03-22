<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Model\ResourceModel\Topic;
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct()
    {
        $this->_init('Tigren\HelloWorld\Model\Topic',
            'Tigren\HelloWorld\Model\ResourceModel\Topic');
    }
}
