<?php
namespace Rsgitech\Member\Model\Allmember;

use Rsgitech\Member\Model\ResourceModel\Allmember\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Rsgitech\Member\Model\ResourceModel\Allmember\Collection
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
     * @param CollectionFactory $allmemberCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $allmemberCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $allmemberCollectionFactory->create();
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
        /** @var $member \Rsgitech\Member\Model\Allmember */
        foreach ($items as $member) {
            $this->loadedData[$member->getId()] = $member->getData();
        }

        $data = $this->dataPersistor->get('member_allmember');
        if (!empty($data)) {
            $member = $this->collection->getNewEmptyItem();
            $member->setData($data);
            $this->loadedData[$member->getId()] = $member->getData();
            $this->dataPersistor->clear('member_allmember');
        }

        return $this->loadedData;
    }
}
