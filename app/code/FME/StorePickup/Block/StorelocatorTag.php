<?php
namespace FME\StorePickup\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class StorelocatorTag extends Template
{
          
    protected $scopeConfig;
    protected $collectionFactory;
    protected $objectManager;
    public    $googleMapsStoreHelper;
        
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \FME\StorePickup\Helper\Data $helper,
        \FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory $collectionFactory,
        \FME\StorePickup\Model\TagsFactory $tagsFactory,
        ObjectManagerInterface $objectManager
    ) {
        
        $this->collectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->tagsFactory = $tagsFactory;
        $this->googleMapsStoreHelper = $helper;
        parent::__construct($context);
    }
    public function _prepareLayout()
    {   
        $tag = $this->getCurrentTag();
        if ($this->googleMapsStoreHelper->isEnabledInFrontend()) {
            $this->pageConfig->setKeywords($this->googleMapsStoreHelper->getGMapMetaKeywords());
            $this->pageConfig->setDescription($this->googleMapsStoreHelper->getGMapMetadescription());
            $this->pageConfig->getTitle()->set($tag->getTagName());
  
            return parent::_prepareLayout();
        }
    }
    public function getCurrentTag()
    {
        $tagId = $this->getRequest()->getParam('id');
        $currentTag = $this->tagsFactory->create()->load($tagId);
        return $currentTag;
    }
    public function getStoreIds()
    {
        $tagId = $this->getRequest()->getParam('id');
        $storeIds = $this->tagsFactory->create()->getTags($tagId);
        return $storeIds;
        
    }
    
    public function getAllStores()
    {
        $storeIds = $this->getStoreIds();
        $collection = $this->collectionFactory->create()->addFieldToFilter('is_active', 1)->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))
                    ->addFieldToFilter('gmaps_id',array('in',$storeIds))
                    ->addStoreFilter($this->_storeManager->getStore())
        ->setOrder('store_name', 'ASC');
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
}
