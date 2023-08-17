<?php
namespace Rsgitech\Member\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
  public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
      
    $memberTableName = $setup->getTable('Rsgitech_member');

    if($setup->getConnection()->isTableExists($memberTableName) != true) {

      $memberTable = $setup->getConnection()
          ->newTable($memberTableName)
          ->addColumn(
              'member_id',
              \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
              null,
              ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
              'Member ID'
          )
          ->addColumn(
              'name',
              \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
              255,
              ['nullable' => false, 'default' => ''],
                'Name'
          )
          ->addColumn(
            'department',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
              'Department'
          )
          ->addColumn(
            'photo',
            \Magento\Framework\DB\Ddl\Table::TYPE_BLOB,
            null,
            ['nullable' => true, 'default' => ''],
            'Photo'
          )
          ->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Title'
          )
          ->addColumn(
            'quotes',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Quotes'
          )	
          ->addIndex(
            $setup->getIdxName('Rsgitech_member', ['name']),
            ['name']
          )
          ->setComment("Member Table");

      $setup->getConnection()->createTable($memberTable);
    }
  }
}
?>