<?php

namespace Rsgitech\Teams\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $date;
 
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->date = $date;
    }
    
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $dataTeamsRows = [
            [
                'department_name' => 'Sales',
                'created_by' => 'Omkar',
                'created_at' => $this->date->date(),
                'updated_by' => 'Omkar',
                'updated_at' => $this->date->date()
            ],
            [
                'department_name' => 'Development',
                'created_by' => 'Omkar',
                'created_at' => $this->date->date(),
                'updated_by' => 'Omkar',
                'updated_at' => $this->date->date()
                
            ]
        ];
        
        foreach($dataTeamsRows as $data) {
            $setup->getConnection()->insert($setup->getTable('rsgitech_teams'), $data);
        }
    }
}
?>