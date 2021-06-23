<?php
namespace FME\StorePickup\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class StorelocatorDetail extends Template
{
          
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;
    public    $googleMapsStoreHelper;
        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \FME\StorePickup\Helper\Data $helper,
        \FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory $collectionFactory,
        \FME\StorePickup\Model\ResourceModel\Timetable\CollectionFactory $timetableCollectionFactory,
        \FME\StorePickup\Model\ResourceModel\Holiday\CollectionFactory $holidayCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_productFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status
        $productStatus,
        \Magento\Catalog\Block\Product\ImageBuilder $_imageBuilder,
        \Magento\Review\Model\ReviewFactory $_reviewFactory,
        \FME\StorePickup\Model\ResourceModel\Tags\CollectionFactory $tagsCollectionFactory,
        \FME\StorePickup\Model\StorePickup  $storelocatorModel,
        ObjectManagerInterface $objectManager
    ) {
        
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->storelocatorModel = $storelocatorModel;
        $this->googleMapsStoreHelper = $helper;
        $this->_reviewFactory=$_reviewFactory;
        $this->_imageBuilder=$_imageBuilder;
        $this->_productFactory = $_productFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;             
        $this->timetableCollectionFactory = $timetableCollectionFactory;
        $this->holidayCollectionFactory = $holidayCollectionFactory;
        $this->tagsCollectionFactory = $tagsCollectionFactory;
        parent::__construct($context);
    }
    public function _prepareLayout()
    {
        $currentStore = $this->getAllStores()->getFirstItem();
        if ($this->googleMapsStoreHelper->isEnabledInFrontend()) {
            $this->pageConfig->setKeywords($currentStore->getMetaKeyword());
            $this->pageConfig->setDescription($currentStore->getMetaDescription());
            $this->pageConfig->getTitle()->set($currentStore->getStoreName());
  
            return parent::_prepareLayout();
        }
    }
    
    public function getAllStores()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = $this->collectionFactory->create()->addFieldToFilter('is_active', 1)->addFieldToFilter('gmaps_id',$id)
        ->setOrder('creation_time', 'DESC');
        return $collection;
    }
    public function getMediaUrl()
    {
        return $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'storepickup/';
    }
    public function getStoresUrl($identifier)
    {

        $suffix = $this->googleMapsStoreHelper->getGMapSeoSuffix();
        $urlKey = $identifier.$suffix;
        return $this->getUrl($urlKey);

    }
    public function getStoresHours($timetableId)
    {
        $collection = $this->timetableCollectionFactory->create()->addFieldToFilter('timetable_id',$timetableId)->getFirstItem();
        return $collection;

    }
    public function getStoresHolidays($gmapId)
    {
        $holidayIds = $this->storelocatorModel->getHoliday($gmapId);
        $collection = $this->holidayCollectionFactory->create()->addFieldToFilter('holiday_id',array('in',$holidayIds));
        return $collection;

    }
    public function getStoresTages($gmapId)
    {
        $tagIds = $this->storelocatorModel->getTags($gmapId);
        $collection = $this->tagsCollectionFactory->create()->addFieldToFilter('tag_id',array('in',$tagIds));
        return $collection;

    }
    public function getStoresProducts($gmapId)
    {
        $productIds = $this->storelocatorModel->getRelatedProducts($gmapId);
         if($productIds):
                $productCollection = $this->_productFactory->create()->addAttributeToSelect(array('*'))->addFieldToFilter('sku',array('in',$productIds));
                $productCollection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
                $productCollection->setVisibility($this->productVisibility->getVisibleInSiteIds());
            return $productCollection->load();
        endif;

    }
    public function getRatingSummary($product)
    {
        $this->_reviewFactory->create()->getEntitySummary(
            $product,
            $this->_storeManager->getStore()->getId()
        );
        $ratingSummary = ($product->getRatingSummary()->getRatingSummary()) ?
            $product->getRatingSummary()->getRatingSummary() : 0 ;
        return $ratingSummary;
    }
    
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}
