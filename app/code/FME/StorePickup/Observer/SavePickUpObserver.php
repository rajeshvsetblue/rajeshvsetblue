<?php
namespace FME\StorePickup\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
class SavePickUpObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectmanager,
                                \FME\StorePickup\Helper\Data $helperData
                                    )
    {
        $this->_objectManager = $objectmanager;
        $this->helperData = $helperData;
    }
    public function execute(EventObserver $observer)
    {
        $order = $observer->getOrder();
        $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');
        $quote = $quoteRepository->get($order->getQuoteId());
        $shippingInfo = $order->getShippingAddress();
        if($order->getShippingMethod() == 'fmestorepickup_fmestorepickup'):
        $storeList = $this->helperData->getInStoreList();
        $storeID = $quote->getFmestorepickupId();
        $currentStoreAdd = $storeList[$storeID];
        $order->setFmestorepickupId( $quote->getFmestorepickupId() );
        $order->setFmepickupDatetime( $quote->getFmestorepickupId() );
        $shippingInfo->setFirstname($currentStoreAdd['first_name']);
        $shippingInfo->setLastname($currentStoreAdd['last_name']);
        $shippingInfo->setStreet($currentStoreAdd['street']);
        $shippingInfo->setTelephone($currentStoreAdd['telephone']);
        $shippingInfo->setPostcode($currentStoreAdd['telephone']);
        $shippingInfo->setRegion($currentStoreAdd['region']);
        $shippingInfo->setRegionId($currentStoreAdd['region_id']);
        $shippingInfo->setCountryId($currentStoreAdd['country_id']);
        $shipping = $order->getShippingDescription().'  '.$currentStoreAdd['last_name'].'  '.$quote->getFmepickupDatetime();

        $order->setShippingDescription($shipping);
        endif;
 
        return $this;
    }
}