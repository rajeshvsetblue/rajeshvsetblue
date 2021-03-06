<?php
namespace FME\StorePickup\Plugin\Checkout\Model;
class ShippingInformationManagement
{
    protected $quoteRepository;
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $quote = $this->quoteRepository->getActive($cartId);
        $storepickupId = $extAttributes->getFmestorepickupid();
        $pickuptimedate = $extAttributes->getFmepickupdatetime();
        $quote->setFmestorepickupId($storepickupId);
        $quote->setFmepickupDatetime($pickuptimedate);
    }
}