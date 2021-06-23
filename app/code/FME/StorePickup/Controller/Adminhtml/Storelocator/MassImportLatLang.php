<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Storelocator;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use FME\StorePickup\Model\ResourceModel\StorePickup\CollectionFactory;

/**
 * Class MassEnable
 */
class MassImportLatLang extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter,        \FME\StorePickup\Helper\Data $helper,
 CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->googleMapsStoreHelper = $helper;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $n=0;
        foreach ($collection as $item) {
       
            $item_address = $item->getAddress().', '.$item->getCity().', '.$item->getPostcode().', '.$item->getCountryId();
            $latLang = $this->getLatLangFromAddress($item_address);
                    if($latLang['success']=='OK'):

                      $item->setLatitude($latLang['latitude']);
                      $item->setLongitude($latLang['longtitude']);
                      $item->save();
                      if($n%4==0):
                        sleep(6);
                      endif;
                        $n=$n+1;
                    endif;
            
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $n));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
    
    public function getLatLangFromAddress($address)
    {
      $key = '';
      if($mapKey = $this->googleMapsStoreHelper->getGMapAPIKey()):

        $key = '&key='.$mapKey;

      endif;

       $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false'.$key);
      $geo = json_decode($geo, true);
      if ($geo['status'] == 'OK') {

          $latitude = $geo['results'][0]['geometry']['location']['lat'];
          $longitude = $geo['results'][0]['geometry']['location']['lng'];
          return $resultjson = ['success' => 'OK', 'latitude' => $latitude, 'longtitude' => $longitude];

      }else {
        
          return $resultjson = ['success' => ''];

      }
    }
}
