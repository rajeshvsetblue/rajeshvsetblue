<?php


/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Timetable;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
       
        return $this->_authorization
                    ->isAllowed('FME_StorePickup::manage_timetable');
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('FME_StorePickup::timetable');
        $resultPage->addBreadcrumb(__('Timetable'), __('Timetable'));
        $resultPage->addBreadcrumb(__('Manage Timetable'), __('Manage Timetable'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Timetable'));
        return $resultPage;
    }
}
