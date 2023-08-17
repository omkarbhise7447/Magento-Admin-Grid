<?php
namespace Rsgitech\Teams\Model\Allteams;

use Rsgitech\Teams\Model\ResourceModel\Allteams\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Rsgitech\Teams\Model\ResourceModel\Allteams\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $allteamsCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $allteamsCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $allteamsCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $teams \Rsgitech\Teams\Model\Allteams */
        foreach ($items as $teams) {
            $this->loadedData[$teams->getId()] = $teams->getData();
        }

        $data = $this->dataPersistor->get('teams_allteams');
        if (!empty($data)) {
            $teams = $this->collection->getNewEmptyItem();
            $teams->setData($data);
            $this->loadedData[$teams->getId()] = $teams->getData();
            $this->dataPersistor->clear('teams_allteams');
        }

        return $this->loadedData;
    }
}
