<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use FME\StorePickup\Block\Adminhtml\Timetable\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;

/**
 * Class PageActions
 */
class Timetable extends Column
{
    /** Url path */
    const TIMETABLE_URL_PATH_EDIT = 'storepickup/timetable/edit';
    const TIMETABLE_URL_PATH_DELETE = 'storepickup/timetable/delete';

    /** @var UrlBuilder */
    protected $actionUrlBuilder;
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::TIMETABLE_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
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
                if (isset($item['timetable_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['timetable_id' => $item['timetable_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::TIMETABLE_URL_PATH_DELETE,
                            ['timetable_id' => $item['timetable_id']]
                        ),
                       'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete \"${ $.$data.timetable_name }\"'),
                            'message' => __('Are you sure you wan\'t to delete a \"${ $.$data.timetable_name }\" record?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
