<?php
namespace FME\StorePickup\Block;
 

use Magento\Framework\ObjectManagerInterface;

class ProductStores extends \Magento\Catalog\Block\Product\AbstractProduct
{
          
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;
    protected $modelStore;
    public    $googleMapsStoreHelper;
        
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \FME\StorePickup\Helper\Data $helper,
        \FME\StorePickup\Model\ResourceModel\Tags\CollectionFactory $tagscollectionFactory,
        \FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager,
        \FME\StorePickup\Model\StorePickup $modelStore,    
        array $data = []    
    ) {
        
        $this->tagscollectionFactory = $tagscollectionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->googleMapsStoreHelper = $helper;
        $this->modelStore = $modelStore;
        
        parent::__construct($context, $data);
    }
    
    
    public function getProductStores()
    {       
        $current_sku = $this->getProduct()->getSku();
        
        $collection = $this->modelStore->getProductAtStores($current_sku);
        $available_collection = $collection->addFieldToFilter('is_active', 1)->addStoreFilter($this->_storeManager->getStore())
            ->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))->setOrder('store_name', 'ASC');               
        
        
        return $available_collection;
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
    
}
