<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_faq'))
            ->addColumn(
                'faq_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' =>
                        true,
                    'unsigned' => true
                ],
                'Faq Id'
            )->addColumn(
                'question',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Faq question'
            )->addColumn(
                'author',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Faq author'
            )->addColumn(
                'answer',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Faq answer'
            )->addColumn(
                'status',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Faq status'
            )->addColumn(
                'product',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Faq product'
            )->addColumn(
                'position',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Faq position'
            )->setComment(
                'Tigren Faq Table'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
