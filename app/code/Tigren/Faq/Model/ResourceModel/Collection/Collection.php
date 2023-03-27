<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
 */

namespace Tigren\Faq\Model\ResourceModel\Collection;
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct()
    {
        $this->_init('Tigren\Faq\Model\Collection','Tigren\Faq\Model\ResourceModel\Collection');
    }
}
