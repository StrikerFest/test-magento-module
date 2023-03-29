<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule'))
            ->addColumn(
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' =>
                        true,
                    'unsigned' => true
                ],
                'Rule Id'
            )->addColumn(
                'name',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Rule name'
            )->addColumn(
                'discountAmount',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Rule discount amount'
            )->addColumn(
                'startTime',
                \Magento\Framework\Db\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Rule start time'
            )->addColumn(
                'endTime',
                \Magento\Framework\Db\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Rule end time'
            )->addColumn(
                'priority',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Rule priority'
            )->addColumn(
                'active',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Rule active'
            )->setComment(
                'Tigren Rule Table'
            );
        $installer->getConnection()->createTable($table);


        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_customer_group'))
            ->addColumn(
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                ],
                'Rule Id'
            )->addColumn(
                'customerGroup_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Rule customer group'
            )->setComment(
                'Tigren Rule Customer group'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_store'))
            ->addColumn(
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                ],
                'Rule Id'
            )->addColumn(
                'store_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                ],
                'Rule store'
            )->setComment(
                'Tigren Rule Store'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_products'))
            ->addColumn(
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'primary' => true,
                ],
                'Rule Id'
            )->addColumn(
                'product_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true,],
                'Rule products'
            )->setComment(
                'Tigren Rule Products'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}

//            ->addColumn(
//                'customerGroup',
//                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
//                255,
//                ['nullable' => false],
//                'Rule customer group'
//            )
//            ->addColumn(
//                'store',
//                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
//                10,
//                ['nullable' => false],
//                'Rule store'
//            )
//            ->addColumn(
//                'products',
//                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
//                255,
//                ['nullable' => false],
//                'Rule products'
//            )
