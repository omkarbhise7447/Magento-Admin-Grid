<?php

namespace Rsgitech\Member\Setup;

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
        $dataMemberRows = [
            [
                'name' => 'Tejas Singh',
                'department' => 'Devolopment',
                'photo' => '',
                // 'photo' => $this->date->date(),
                'title' => 'Manager',
                'quotes' => 'We are what we repeatedly do'
            ],
            [
                'name' => 'Anjali Sharma',
                'department' => 'Sales',
                'photo' => '',
                // 'photo' => $this->date->date(),
                'title' => 'Sales consultant',
                'quotes' => "Opportunities don't happen, you create them."
                
            ]
        ];
        
        foreach($dataMemberRows as $data) {
            $setup->getConnection()->insert($setup->getTable('Rsgitech_member'), $data);
        }
    }
}
?>