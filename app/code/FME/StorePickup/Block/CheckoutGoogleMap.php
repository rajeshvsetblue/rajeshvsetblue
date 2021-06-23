<?php
namespace FME\StorePickup\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class CheckoutGoogleMap extends Template
{
          
    protected $scopeConfig;
    protected $storePickupFactory;
    protected $objectManager;
    public    $googleMapsStoreHelper;
        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \FME\StorePickup\Helper\Data $helper,
        \FME\StorePickup\Model\StorePickupFactory $storePickupFactory,
        \FME\StorePickup\Model\ResourceModel\Timetable\CollectionFactory $timetableCollectionFactory,
        \FME\StorePickup\Model\TagsFactory $tagsFactory,
        ObjectManagerInterface $objectManager
    ) {
        
        $this->storePickupFactory = $storePickupFactory;
        $this->objectManager = $objectManager;
        $this->tagsFactory = $tagsFactory;
        $this->googleMapsStoreHelper = $helper;
        $this->timetableCollectionFactory = $timetableCollectionFactory;
        parent::__construct($context);
    }
  


}