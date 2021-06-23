<?php
namespace FME\StorePickup\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;

/**
 * Class InStorePickup
 * @package Arsal\InStore\Model\Carrier
 */
class StorePickup extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface {

    const FME_STORE_PICKUP_CODE = 'fmestorepickup';

    /**
     * @var string $_code
     */
    protected $_code = self::FME_STORE_PICKUP_CODE;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $_rateMethodFactory;

    /**
     * Shipping constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [
            $this->_code        =>  $this->getConfigData('name')
        ];
    }

    /**
     * @param RateRequest $request
     * @return bool|\Magento\Shipping\Model\Rate\Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = $this->getConfigData('shipping_subtotal');

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);
        return $result;
    }

    public function isTrackingAvailable() {
        return true;
    }
}