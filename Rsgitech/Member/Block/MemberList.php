<?php
namespace Rsgitech\Member\Block;

use Magento\Framework\View\Element\Template;
use Rsgitech\Member\Model\ResourceModel\Allmember\CollectionFactory;

class MemberList extends Template
{
    protected $memberCollectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $memberCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->memberCollectionFactory = $memberCollectionFactory;
    }

    public function getGroupedMembersByDepartment()
    {
        $members = $this->memberCollectionFactory->create();
        $members->addFieldToSelect(['member_id', 'name', 'department_name', 'photo', 'title', 'quotes']);
        $members->addOrder('department_name');
        $members->setPageSize(false);

        $groupedMembers = [];
        foreach ($members as $member) {
            $departmentName = $member->getDepartmentName();
            $groupedMembers[$departmentName][] = $member;
        }

        return $groupedMembers;
    }
}
