<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Tabs for Magento 2
 */

namespace Amasty\CustomTabs\Model\Tabs\ResourceModel;

use Amasty\CustomTabs\Api\Data\TabsInterface;
use Magento\Framework\DB\Helper;

/**
 * Trait CollectionTrait
 */
trait CollectionTrait
{
    /**
     * @param \Magento\Framework\DB\Select $select
     * @param bool $group
     */
    public function joinStores($select, $group = true)
    {
        $select->joinLeft(
            ['stores_table' => $this->getTable(TabsInterface::STORE_TABLE_NAME)],
            'main_table.' . TabsInterface::TAB_ID . ' = stores_table.' . TabsInterface::TAB_ID,
            []
        );

        if ($group) {
            $select->group('main_table.tab_id');
            $this->dbHelper->addGroupConcatColumn(
                $select,
                'stores',
                'DISTINCT stores_table.store_id'
            );
        }
    }
}
