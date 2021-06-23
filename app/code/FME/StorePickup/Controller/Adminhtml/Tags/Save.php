<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
use FME\StorePickup\Model\Tags;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'FME_StorePickup::manage_tags';

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
        Tags $model,
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
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Tags::STATUS_ENABLED;
            }
            if (empty($data['tag_id'])) {
                $data['tag_id'] = null;
            }

            $id = $this->getRequest()->getParam('tag_id');
            if ($id) {
                $this->model->load($id);
            }
            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] ='/storepickup/tags/'.$data['image'][0]['name'];
            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] =$data['image'][0]['name'];
            } else {
                $data['image'] = null;
            }

            $this->model->setData($data);
            $this->_eventManager->dispatch(
                'storepickup_tags_prepare_save',
                ['Tags' => $this->model, 'request' => $this->getRequest()]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['tag_id' => $this->model->getId(), '_current' => true]);
            }

            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the tag.'));
                $this->dataPersistor->clear('StorePickup');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['tag_id' => $this->model->getId(),
                         '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the tag.'));
            }

            $this->dataPersistor->set('StorePickup', $data);
            return $resultRedirect->setPath('*/*/edit', ['tag_id' => $this->getRequest()->getParam('tag_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
