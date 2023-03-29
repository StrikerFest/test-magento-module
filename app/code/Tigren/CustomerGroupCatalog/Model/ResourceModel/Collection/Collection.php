<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Model\ResourceModel\Collection;
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    protected function _construct()
    {
        $this->_init('Tigren\CustomerGroupCatalog\Model\Collection','Tigren\CustomerGroupCatalog\Model\ResourceModel\Collection');
    }
}
