<?php
namespace Rsgitech\Teams\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
  public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
      
    $teamsTableName = $setup->getTable('rsgitech_teams');

    if($setup->getConnection()->isTableExists($teamsTableName) != true) {

      $teamsTable = $setup->getConnection()
          ->newTable($teamsTableName)
          ->addColumn(
              'teams_id',
              \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
              null,
              ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
              'Teams ID'
          )
          ->addColumn(
              'department_name',
              \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
              255,
              ['nullable' => false, 'default' => ''],
                'Department Name'
          )
          ->addColumn(
              'created_by',
              \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
              255,
              ['nullable' => false, 'default' => ''],
                'Created By'
          )
          ->addColumn(
              'created_at',
              \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
              null,
              ['nullable' => false],
                'Created At'
          )
          ->addColumn(
            'updated_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
              'Updated By'
        )
          ->addColumn(
              'updated_at',
              \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
              null,
              ['nullable' => false],
                'Updated At'
          )
          ->addIndex(
            $setup->getIdxName('rsgitech_teams', ['department_name']),
            ['department_name']
          )
          ->setComment("Teams Table");

      $setup->getConnection()->createTable($teamsTable);
    }

    // Create 'members' table
        $membersTable = $installer->getConnection()->newTable(
            $installer->getTable('dtx_teams_member')
        )->addColumn(
            'members_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Members ID'
        )->addColumn(
            'team_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Team ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Name'
        )->addColumn(
            'department_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Department Name'
        )->addColumn(
            'photo',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Photo'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Title'
        )->addColumn(
            'quotes',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Quotes'
        )->addForeignKey(
            $installer->getFkName('dtx_teams_member', 'team_id', 'dtx_teams_team', 'teams_id'),
            'team_id',
            $installer->getTable('dtx_teams_team'),
            'teams_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Members Table'
        );
        $installer->getConnection()->createTable($membersTable);
    
  }
}
?>