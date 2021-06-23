<?php
namespace FME\StorePickup\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class Storelocator extends Template
{
          
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;
    public    $googleMapsStoreHelper;
        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \FME\StorePickup\Helper\Data $helper,
        \FME\StorePickup\Model\ResourceModel\Tags\CollectionFactory $tagscollectionFactory,
        \FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager
    ) {
        
        $this->tagscollectionFactory = $tagscollectionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->googleMapsStoreHelper = $helper;
        parent::__construct($context);
    }
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->googleMapsStoreHelper->isEnabledInFrontend()) {
            $this->pageConfig->setKeywords($this->googleMapsStoreHelper->getGMapMetaKeywords());
            $this->pageConfig->setDescription($this->googleMapsStoreHelper->getGMapMetadescription());
            $this->pageConfig->getTitle()->set($this->googleMapsStoreHelper->getGMapPageTitle());
  
            return $this;
        }
    }
    
    public function getAllStores()
    {       
        $collection = $this->collectionFactory->create()->addFieldToFilter('is_active', 1)->addStoreFilter($this->_storeManager->getStore())
        ->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))->setOrder('store_name', 'ASC');
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
    public function getAllTag()
    {
        $currentTag = $this->tagscollectionFactory->create();
        return $currentTag;
    }
}
