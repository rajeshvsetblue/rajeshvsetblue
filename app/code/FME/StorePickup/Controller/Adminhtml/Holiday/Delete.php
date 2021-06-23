<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Holiday;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    
    const ADMIN_RESOURCE = 'FME_StorePickup::holiday_delete';
    
    protected $model;
    public function __construct(
        Action\Context $context,
        \FME\StorePickup\Model\Holiday $model
    ) {
        $this->model = $model;
        parent::__construct($context);
    }
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('holiday_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                $this->model->load($id);
                $title = $this->model->getTitle();
                $this->model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The holiday has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_holidaypage_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_holidaypage_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a holiday to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
