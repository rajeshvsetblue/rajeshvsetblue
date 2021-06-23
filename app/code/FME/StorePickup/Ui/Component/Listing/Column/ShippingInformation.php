<?php
namespace FME\StorePickup\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Quote\Model\QuoteFactory;
use \FME\StorePickup\Helper\Data as InStoreDataHelper;

/**
 * Class ShippingInformation
 * @package Arsal\InStore\Ui\Component\Listing\Column
 */
class ShippingInformation extends Column {

    /**
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * ShippingInformation constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param OrderRepositoryInterface $orderRepositoryInterface
     * @param QuoteFactory $quoteFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepositoryInterface,
        QuoteFactory $quoteFactory,
        array $components = [],
        array $data = []
    )
    {
        $this->_orderRepository = $orderRepositoryInterface;
        $this->quoteFactory = $quoteFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $order  = $this->_orderRepository->get($item["entity_id"]);
                $quote = $this->quoteFactory->create()->load($order->getQuoteId());

                if ($quote->getId()) {
                    if (!empty($quote->getData('instore_code'))) {
                        $storeName = InStoreDataHelper::getStoreNameByCode($quote->getData('instore_code'));
                        if ( !empty($storeName) ) {
                            $shippingInfo = __( '%1 ( %2 )',
                                $item["shipping_information"],
                                $storeName
                            );
                            $item[$this->getData('name')] = $shippingInfo;
                        }
                    }
                }
            }
        }
        return $dataSource;
    }
}
