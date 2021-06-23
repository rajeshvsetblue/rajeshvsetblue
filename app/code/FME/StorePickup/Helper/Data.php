<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FME\StorePickup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;

class Data extends AbstractHelper
{
    protected $_filesystem ;
    protected $_imageFactory;
    static private $staticCollectionFactory = 0;
    static private $staticStoreManger = 0;

    const XML_PATH_ENABLED                      =   'storepickup/general/enable';
    const XML_GMAP_PAGE_TITLE                   =   'storepickup/general/page_title';
    const XML_GMAP_IDENTIFIER                   =   'storepickup/general/identifier';
    const XML_GMAP_PAGE_METAKEYWORD             =   'storepickup/general/meta_keywords';
    const XML_GMAP_PAGE_METADESCRIPTION         =   'storepickup/general/meta_description';
    const XML_GMAP_STANDARD_LATITUDE            =   'storepickup/general/std_latitude';
    const XML_GMAP_STANDARD_LONGITUDE           =   'storepickup/general/std_longitude';
    const XML_GMAP_STANDARD_STORETITLE          =   'storepickup/general/std_strtitle';
    const XML_GMAP_STANDARD_DESCRIPTION         =   'storepickup/general/std_strdescription';
    const XML_GMAP_PAGE_HEADING                 =   'storepickup/general/page_heading';
    const XML_GMAP_PAGE_SUBHEADING              =   'storepickup/general/page_subheading';
    const XML_GMAP_API_KEY                      =   'storepickup/general/api_key';
    const XML_GMAP_HEADERLINK_ENABLE            =   'storepickup/manage_links/link_enable';
    const XML_GMAP_HEADERLINK_TEXT              =   'storepickup/manage_links/label_header';
    const XML_GMAP_FOOTERLINK_ENABLE            =   'storepickup/manage_links/footer_link_enable';
    const XML_GMAP_FOOTERLINK_TEXT              =   'storepickup/manage_links/label_footer';
    const XML_GMAP_SEO_IDENTIFIER               =   'storepickup/seo_suffix/gmap_identifier';
    const XML_GMAP_SEO_SUFFIX                   =   'storepickup/seo_suffix/url_suffix';
    const XML_GMAP_ZOOM                         =   'storepickup/general/map_zoom';
    const XML_GMAP_STORE_IMAGE                  =   'storepickup/general/store_image';
    const XML_GMAP_MARKER_IMAGE                 =   'storepickup/general/marker_image'; 
    const XML_GMAP_DEFAULT_RADIUS               =   'storepickup/storelocatore_search/defaut_radius'; 
    const XML_GMAP_MAX_RADIUS                   =   'storepickup/storelocatore_search/maximum_radius'; 
    const XML_GMAP_TAG_URL                      =   'storepickup/seo_suffix/tag_url'; 
    const XML_GMAP_FB_COMMENTS_ENABLE           =   'storepickup/facebook_comments/fb_comments_enable'; 
    const XML_GMAP_FB_APP_ID                    =   'storepickup/facebook_comments/fb_app_id'; 

    private $_regionFactory;
    public function __construct(    
        \Magento\Framework\App\Helper\Context $context,        
        \Magento\Framework\Filesystem $filesystem, 
        \FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,       
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory
        ) 
    {   
        parent::__construct($context);     
        $this->_filesystem = $filesystem;  
        $this->collectionFactory = $collectionFactory;
        self::$staticCollectionFactory = $collectionFactory;  
        $this->_storeManager = $storeManager;
        self::$staticStoreManger = $storeManager;             
        $this->_imageFactory = $imageFactory;
        $this->_regionFactory = $regionFactory;
        
        
    }
    
    public function getGMapFBCommentsEnable()
    {
            return $this->scopeConfig->getValue(self::XML_GMAP_FB_COMMENTS_ENABLE, ScopeInterface::SCOPE_STORE);
    }
    
    public function getGMapFBAppID()
    {
            return $this->scopeConfig->getValue(self::XML_GMAP_FB_APP_ID, ScopeInterface::SCOPE_STORE);
    }
    
    
    public function isEnabledInFrontend()
    {
         $isEnabled = true;
         $enabled = $this->scopeConfig->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
        if ($enabled == null || $enabled == '0') {
            $isEnabled = false;
        }
         return $isEnabled;
    }
    public function getGMapPageTitle()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_PAGE_TITLE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapMetaKeywords()
    {

        return $this->scopeConfig->getValue(self::XML_GMAP_PAGE_METAKEYWORD, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapMetadescription()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_PAGE_METADESCRIPTION, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapStandardLatitude()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_STANDARD_LATITUDE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapStoreImage()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_STORE_IMAGE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapMarkerImage()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_MARKER_IMAGE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapStandardLongitude()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_STANDARD_LONGITUDE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapStandardTitle()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_STANDARD_STORETITLE, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapDefaultRadius()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_DEFAULT_RADIUS, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapMaxRadius()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_MAX_RADIUS, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapStandardDescription()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_STANDARD_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapPageHeading()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_PAGE_HEADING, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapPageSubheading()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_PAGE_SUBHEADING, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapAPIKey()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_API_KEY, ScopeInterface::SCOPE_STORE);
    }
    public function isHeaderLinkEnable()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_HEADERLINK_ENABLE, ScopeInterface::SCOPE_STORE);
    }
    public function getHeaderLinkLabel()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_HEADERLINK_TEXT, ScopeInterface::SCOPE_STORE);
    }
    public function isFooterLinkEnable()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_FOOTERLINK_ENABLE, ScopeInterface::SCOPE_STORE);
    }
    public function getFooterLinkLabel()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_FOOTERLINK_TEXT, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapSeoSuffix()
    {
        
        return $this->scopeConfig->getValue(self::XML_GMAP_SEO_SUFFIX, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapSeoIdentifier()
    {
            return $this->scopeConfig->getValue(self::XML_GMAP_SEO_IDENTIFIER, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapTagUrl()
    {
            return $this->scopeConfig->getValue(self::XML_GMAP_TAG_URL, ScopeInterface::SCOPE_STORE);
    }
    public function getGMapLink()
    {
        $identifier = $this->getGMapSeoIdentifier();
        $seo_suffix = $this->getGMapSeoSuffix();
        return $identifier.$seo_suffix;
    }
    public function getGMapZoom()
    {
        if (self::XML_GMAP_ZOOM =='') {
            return 8;
        }
        return $this->scopeConfig->getValue(self::XML_GMAP_ZOOM, ScopeInterface::SCOPE_STORE);
    }
    public function resize($image, $width = null, $height = null)
    {
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('storepickup/').$image; //complete path of image

        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('storepickup/resized/'.$width.'/').$image;        
        //create image factory...
        $imageResize = $this->_imageFactory->create();        
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(TRUE);        
        $imageResize->keepTransparency(TRUE);        
        $imageResize->keepFrame(FALSE);        
        $imageResize->keepAspectRatio(TRUE);        
        $imageResize->resize($width,$height);  
        //destination folder                
        $destination = $imageResized ;    
        //save image      
        $imageResize->save($destination);        

        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'storepickup/resized/'.$width.'/'.$image;
        return $resizedURL;
     }
     public static function getStoresList() {

        $collection = self::$staticCollectionFactory->create()->addFieldToFilter('is_active', 1)->addStoreFilter(self::$staticStoreManger->getStore())
        ->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))->setOrder('store_name', 'ASC');
        $optionArray = array();        
        foreach ($collection as $store) {
            $optionArray[] = [
                "value"                 =>  $store->getId(),
                "label"                  =>  $store->getStoreName()
            ];
        }
        return $optionArray;;
    }
   
    public function getInStoreList() {

        $collection = $this->collectionFactory->create()->addFieldToFilter('is_active', 1)->addStoreFilter($this->_storeManager->getStore())
        ->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))->setOrder('store_name', 'ASC');
        $optionArray = array();        
        foreach ($collection as $store) {
            $optionArray[$store->getId()] =  [
                                    'first_name'      =>  'Store Pickup -',
                                    'last_name'     =>  $store->getStoreName(),
                                     "street"            =>    $store->getAddress(),  
                                        "city"              =>  $store->getCity(),
                                        "region"            =>  $store->getRegion(),
                                        "region_id"         =>   $store->getRegionId(),
                                        "postcode"        =>    $store->getPostcode(),
                                        "telephone"         =>  $store->getStorePhone(),
                                        "country_id"         =>   $store->getCountryId()
                                    ]
                                ;
       
        }
        return $optionArray;
       
    }
    public function getFirstStore() {

        $collection = $this->collectionFactory->create()->addFieldToFilter('is_active', 1)->addStoreFilter($this->_storeManager->getStore())
        ->addFieldToFilter('longitude', array('neq' => ''))->addFieldToFilter('latitude', array('neq' => ''))->setOrder('store_name', 'ASC')->getFirstItem();

        $html ='<div><p>'.$collection->getStoreName().'<br />'.$collection->getAddress().'<br />Tel:'. $collection->getStorePhone().'</p>';
               
  
        return $html;
       
    }

   
}
