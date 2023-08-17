<?php

namespace Rsgitech\Teams\Model;

use Rsgitech\Teams\Api\Data;
use Rsgitech\Teams\Api\AllteamsRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Rsgitech\Teams\Model\ResourceModel\Allteams as ResourceAllteams;
use Rsgitech\Teams\Model\ResourceModel\Allteams\CollectionFactory as AllteamsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class AllteamsRepository implements AllteamsRepositoryInterface
{
    protected $resource;

    protected $allteamsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataAllteamsFactory;

    private $storeManager;

    public function __construct(
        ResourceAllteams $resource,
        AllteamsFactory $allteamsFactory,
        Data\AllteamsInterfaceFactory $dataAllteamsFactory,
        DataObjectHelper $dataObjectHelper,
		DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
		$this->allteamsFactory = $allteamsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAllteamsFactory = $dataAllteamsFactory;
		$this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    public function save(\Rsgitech\Teams\Api\Data\AllteamsInterface $teams)
    {
        if ($teams->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $teams->setStoreId($storeId);
        }
        try {
            $this->resource->save($teams);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the teams: %1', $exception->getMessage()),
                $exception
            );
        }
        return $teams;
    }

    public function getById($teamsId)
    {
		$teams = $this->allteamsFactory->create();
        $teams->load($teamsId);
        if (!$teams->getId()) {
            throw new NoSuchEntityException(__('Teams with id "%1" does not exist.', $teamsId));
        }
        return $teams;
    }
	
    public function delete(\Rsgitech\Teams\Api\Data\AllteamsInterface $teams)
    {
        try {
            $this->resource->delete($teams);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the teams: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($teamsId)
    {
        return $this->delete($this->getById($teamsId));
    }
}
?>
