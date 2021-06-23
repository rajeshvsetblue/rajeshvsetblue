<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use FME\StorePickup\Model\StorePickup;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'FME_StorePickup::manage_stores';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    protected $model;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        StorePickup $model,
        \FME\StorePickup\Helper\Data $helper,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\App\Filesystem\DirectoryList $directorylist 
    ) {
        $this->model = $model;
        $this->directorylist=$directorylist;
        $this->filterManager = $filterManager;
        $this->googleMapsStoreHelper = $helper;
        $this->csvProcessor = $csvProcessor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        $data = $this->getRequest()->getPostValue();
    
        
       
        $mediaUrl = $this->directorylist->getPath('media');;
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($data) {
            
            if (isset($data['import_file'][0]['name']) && isset($data['import_file'][0]['tmp_name'])) {
                
                $fileImport =$mediaUrl.'/storepickup/import/'.$data['import_file'][0]['name'];             
                
            }else{
                
                $this->messageManager->addError(__('Upload CSV file to import data from.'));
                return $resultRedirect->setPath('storepickup/storelocator/import');
            }
            
            $importProductRawData = $this->csvProcessor->getData($fileImport);
            $columns = $importProductRawData[0];
            
            if(count($columns) < 11){ 
                
                $this->messageManager->addError(__('Uploaded CSV file is not correct, must have 11 number of columns, %1 provided.', count($columns)));
                return $resultRedirect->setPath('storepickup/storelocator/import');                
            }
            

            $q=1;
            $n=1;
            $j=0;
          
            foreach ($importProductRawData as $rowIndex => $dataRow) {
                if ($q!=1) {
                    
                    $newArray= array();
                    $urlkey = $this->filterManager->translitUrl(strtolower($dataRow[0]).'-'.strtolower(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), -3)));
                    $newArray['store_name']          = $dataRow[0];
                    $newArray['identifier']          = $urlkey;
                    $newArray['address']             = $dataRow[1];//.' '.$dataRow[2].' '.$dataRow[3].' '.$dataRow[1].' '.$dataRow[4];
                    $newArray['latitude']            = $dataRow[2];
                    $newArray['longitude']           = $dataRow[3];
                    $newArray['is_active']           = $dataRow[4];
                    $newArray['zoom_level']          = 10;
                    $newArray['store_phone']         = $dataRow[5];
                    $newArray['store_fax']           = $dataRow[6];
                    $newArray['store_description']   = $dataRow[7];
                    $newArray['postcode']           = $dataRow[8];
                    $newArray['city']               = $dataRow[9];
                    $newArray['country_id']         = $dataRow[10];
                    
                    $newArray['store_id']            = 0;
                    if(!$newArray['latitude'] && !$newArray['longitude']):

                        $latLang = $this->getLatLangFromAddress($newArray['address']);
                        if($latLang['success']=='OK'):
                            $newArray['latitude']        = $latLang['latitude'];
                            $newArray['longitude']       = $latLang['longtitude'];

                            if($n%4==0):
                                sleep(6);
                            endif;
                            $n++;
                        endif;
                    endif;
                    $this->model->setData($newArray);
                    $this->model->save();
                    $j= $j+1;
                }

                $q++;
            }
            
            $import = $q-1;
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been imported.', $import));
            return $resultRedirect->setPath('storepickup/storelocator/import');
            
        }else{
        

            $this->messageManager->addError(__('Error : Upload CSV file to import data.'));
            return $resultRedirect->setPath('storepickup/storelocator/import');
        }
    }
    
    public function getLatLangFromAddress($address)
    {
      $key = '';
      if($mapKey = $this->googleMapsStoreHelper->getGMapAPIKey()):

        $key = '&key='.$mapKey;

      endif;

       $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false'.$key);
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
