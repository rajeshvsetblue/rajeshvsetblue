<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Storelocator;

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
        PostDataProcessor $dataProcessor,
        StorePickup $model,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->model = $model;
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
    
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        // $data['category_products'] = str_replace(str_split('\\:{}"//'), ' ', $data['category_products']);
         
        // $data['category_products'] = explode(',', $data['category_products']);
        if (isset($data["tags_products"])) {
             $cat_array = json_decode($data['tags_products'], true);


             
              $pro_array = array_values($cat_array);
                $c=0;
            foreach ($cat_array as $key => $value) {
                $pro_array[$c] = $key;
                $c++;
            }
              
              unset($data['tags_products']);
              $data['tag_id'] = $pro_array;
        }

        if (isset($data["holiday_products"])) {
             $cat_array = json_decode($data['holiday_products'], true);


             
              $pro_array = array_values($cat_array);
                $c=0;
            foreach ($cat_array as $key => $value) {
                $pro_array[$c] = $key;
                $c++;
            }
              
              unset($data['holiday_products']);
              $data['holiday_id'] = $pro_array;
        }
        if (isset($data["related_products"])) {
             $cat_array = json_decode($data['related_products'], true);


             
              $pro_array = array_values($cat_array);
                $c=0;
            foreach ($cat_array as $key => $value) {
                $pro_array[$c] = $key;
                $c++;
            }
              
              unset($data['related_products']);
              $data['entity_id'] = $pro_array;
        }
     
      
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Storelocator::STATUS_ENABLED;
            }
            if (empty($data['gmaps_id'])) {
                $data['gmaps_id'] = null;
            }

            $id = $this->getRequest()->getParam('gmaps_id');
            if ($id) {
                $this->model->load($id);
            }
            $data['store_id']=implode(',', $data['store_id']);
            $this->model->setData($data);

            $this->_eventManager->dispatch(
                'storesickup_storelocator_prepare_save',
                ['storepickup' => $this->model, 'request' => $this->getRequest()]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['gmaps_id' => $this->model->getId(), '_current' => true]);
            }

            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the store.'));
                $this->dataPersistor->clear('storepickup');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['gmaps_id' => $this->model->getId(),
                         '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the store.'));
            }

            $this->dataPersistor->set('storepickup', $data);
            return $resultRedirect->setPath('*/*/edit', ['gmaps_id' => $this->getRequest()->getParam('gmaps_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
