<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Setup;

/**
 * Class InstallSchema
 * @package Tigren\CustomerGroupCatalog\Setup
 * Tigren Solutions <info@tigren.com>
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
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

        // Compound table ( rule - customerGroup )
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_customer_group'))
            ->addColumn(
                'rule_customerGroup_id',
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
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                ],
                'Rule Id'
            )->addColumn(
                'customerGroup_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,],
                'Rule customer group'
            )->addIndex(
                $installer->getIdxName(
                    'rule_customerGroup_id',
                    ['rule_id', 'customerGroup_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'customerGroup_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment(
                'Tigren Rule Customer group'
            );
        $installer->getConnection()->createTable($table);

        //        Compound table ( rule - store )
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_store'))
            ->addColumn(
                'rule_store_id',
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
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                ],
                'Rule Id'
            )->addColumn(
                'store_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                ],
                'Rule store'
            )->addIndex(
                $installer->getIdxName(
                    'rule_store_id',
                    ['rule_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment(
                'Tigren Rule Store'
            );
        $installer->getConnection()->createTable($table);

        //        Compound table ( rule - products )
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_rule_products'))
            ->addColumn(
                'rule_product_id',
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
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                ],
                'Rule Id'
            )->addColumn(
                'product_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,],
                'Rule products'
            )->addIndex(
                $installer->getIdxName(
                    'rule_product_id',
                    ['rule_id', 'product_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'product_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment(
                'Tigren Rule Products'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_customer_group_catalog_history'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' =>
                        true,
                    'unsigned' => true
                ],
                'Entity id'
            )->addColumn(
                'order_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,],
                'Order products'
            )->addColumn(
                'customer_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,],
                'Customer id'
            )->addColumn(
                'rule_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false,],
                'Rule id'
            )->setComment(
                'Tigren customer group catalog history Table'
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
