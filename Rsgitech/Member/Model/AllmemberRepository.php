<?php

namespace Rsgitech\Member\Model;

use Rsgitech\Member\Api\Data;
use Rsgitech\Member\Api\AllmemberRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Rsgitech\Member\Model\ResourceModel\Allmember as ResourceAllmember;
use Rsgitech\Member\Model\ResourceModel\Allmember\CollectionFactory as AllmemberCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class AllmemberRepository implements AllmemberRepositoryInterface
{
    protected $resource;

    protected $allmemberFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataAllmemberFactory;

    private $storeManager;

    public function __construct(
        ResourceAllmember $resource,
        AllmemberFactory $allmemberFactory,
        Data\AllmemberInterfaceFactory $dataAllmemberFactory,
        DataObjectHelper $dataObjectHelper,
		DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
		$this->allmemberFactory = $allmemberFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAllmemberFactory = $dataAllmemberFactory;
		$this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    public function save(\Rsgitech\Member\Api\Data\AllmemberInterface $member)
    {
        if ($member->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $member->setStoreId($storeId);
        }
        try {
            $this->resource->save($member);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the member: %1', $exception->getMessage()),
                $exception
            );
        }
        return $member;
    }

    public function getById($memberId)
    {
		$member = $this->allmemberFactory->create();
        $member->load($memberId);
        if (!$member->getId()) {
            throw new NoSuchEntityException(__('Member with id "%1" does not exist.', $memberId));
        }
        return $member;
    }
	
    public function delete(\Rsgitech\Member\Api\Data\AllmemberInterface $member)
    {
        try {
            $this->resource->delete($member);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the member: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($memberId)
    {
        return $this->delete($this->getById($memberId));
    }
}
?>
