<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Ui\DataProvider\Rule;


class AddStoreToCollection implements \Magento\Ui\DataProvider\AddFieldToCollectionInterface
{
    public function addField(\Magento\Framework\Data\Collection $collection, $field, $alias = null)
    {
        dd($collection->getItems());
        $collection->joinField('manage_stock', 'cataloginventory_stock_item', 'manage_stock', 'product_id=entity_id', null, 'left');
    }
}

