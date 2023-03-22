<?php
namespace Tigren\HelloWorld\Setup;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface {
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ){
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('Tigren_blog'))
            ->addColumn(
                'blog_id',
                \Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' =>
                    true, 'unsigned' => true],
                'Blog Id'
            )->addColumn(
                'title',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Blog Title'
            )->addColumn(
                'content',
                \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Blog Content'
            )->addColumn(
                'creation_time',
                \Magento\Framework\Db\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' =>
                    \Magento\Framework\Db\Ddl\Table::TIMESTAMP_INIT],
                'Blog Creation Time'
            )->setComment(
                'Tigren Blog Table'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
