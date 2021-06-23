<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Timetable;

use Magento\Backend\App\Action;
use FME\StorePickup\Model\Timetable;
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
    const ADMIN_RESOURCE = 'FME_StorePickup::manage_timetable';

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
        Timetable $model,
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
                $data['is_active'] = Timetable::STATUS_ENABLED;
            }
            if (empty($data['timetable_id'])) {
                $data['timetable_id'] = null;
            }

            $id = $this->getRequest()->getParam('timetable_id');
            if ($id) {
                $this->model->load($id);
            }
            
            $this->model->setData($data);
            $this->_eventManager->dispatch(
                'StorePickup_timetable_prepare_save',
                ['Timetable' => $this->model, 'request' => $this->getRequest()]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['timetable_id' => $this->model->getId(), '_current' => true]);
            }

            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the timetable.'));
                $this->dataPersistor->clear('StorePickup');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['timetable_id' => $this->model->getId(),
                         '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the timetable.'));
            }

            $this->dataPersistor->set('StorePickup', $data);
            return $resultRedirect->setPath('*/*/edit', ['timetable_id' => $this->getRequest()->getParam('timetable_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
