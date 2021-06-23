<?php


/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Holiday;

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
                    ->isAllowed('FME_StorePickup::manage_holiday');
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('FME_StorePickup::holiday');
        $resultPage->addBreadcrumb(__('Holiday'), __('Holiday'));
        $resultPage->addBreadcrumb(__('Manage Holiday'), __('Manage Holidays'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Holidays'));
        return $resultPage;
    }
}
