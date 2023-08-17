<?php
namespace Rsgitech\Member\Model\ResourceModel\Allmember;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'member_id';
	
	protected $_eventPrefix = 'member_allmember_collection';

    protected $_eventObject = 'allmember_collection';
	
	/**
     * Define model & resource model
     */
	protected function _construct()
	{
		$this->_init('Rsgitech\Member\Model\Allmember', 'Rsgitech\Member\Model\ResourceModel\Allmember');
	}
}
?>