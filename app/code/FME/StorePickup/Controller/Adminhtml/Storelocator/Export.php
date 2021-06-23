<?php


namespace FME\StorePickup\Controller\Adminhtml\Storelocator;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use FME\StorePickup\Model\StorePickup;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;

class Export extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    protected $model;
    
    protected $fileFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StorePickup $model,
        FileFactory $fileFactory    
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->model = $model;
        $this->fileFactory = $fileFactory;
    }
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
       
        return $this->_authorization
                    ->isAllowed('FME_StorePickup::manage_stores');
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
         /** start csv content and set template */
        $headers = new \Magento\Framework\DataObject(
            [
                'store_name' => __('Store Name'),                
                'address' => __('Address'),
                'latitude' => __('Latitude'),
                'longitude' => __('Longitude'),
                'is_active' => __('Enable'),
                'store_phone' => __('Phone'),
                'store_fax' => __('Fax'),
                'store_description' => __('Description'),
                'postcode' => __('Postcode'),
                'city' => __('City'),
                'country_id' => __('Country Code')
            ]
        );
        
        
        $template = '"{{store_name}}","{{address}}","{{latitude}}","{{longitude}}","{{is_active}}"' .
            ',"{{store_phone}}","{{store_fax}}","{{store_description}}","{{postcode}}","{{city}}","{{country_id}}"';
        $content = $headers->toString($template);
        $content .= "\n";
        
        
        $collection = $this->model->getCollection();
        

                
        while ($map_store = $collection->fetchItem()) {
            $content .= $map_store->toString($template) . "\n";
        }
        
        return $this->fileFactory->create('StorePickup.csv', $content, DirectoryList::VAR_DIR);
        
    }
}