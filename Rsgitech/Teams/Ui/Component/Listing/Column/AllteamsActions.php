<?php

namespace Rsgitech\Teams\Ui\Component\Listing\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class AllteamsActions
 */
class AllteamsActions extends Column
{
    /** Url path */
    const CMS_URL_PATH_EDIT = 'teams/allteams/edit';
    const CMS_URL_PATH_DELETE = 'teams/allteams/delete';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::CMS_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['teams_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['teams_id' => $item['teams_id']]),
                        'label' => __('Edit')
                    ];
                    $department_name = $this->getEscaper()->escapeHtml($item['department_name']);
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::CMS_URL_PATH_DELETE, ['teams_id' => $item['teams_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'department_name' => __('Delete %1', $department_name),
                            'message' => __('Are you sure you want to delete a %1 record?', $department_name)
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }

    /**
     * Get instance of escaper
     * @return Escaper
     * @deprecated 101.0.7
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}
?>
