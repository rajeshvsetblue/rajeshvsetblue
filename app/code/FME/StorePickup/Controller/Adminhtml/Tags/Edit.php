<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;
 
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'FME_StorePickup::manage_tags';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $model;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \FME\StorePickup\Model\Tags $model,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->model = $model;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('FME_StorePickup::StorePickup_tags')
            ->addBreadcrumb(__('TAGS'), __('TAGS'))
            ->addBreadcrumb(__('Manage Tags'), __('Manage Tags'));
        return $resultPage;
    }
        
    public function execute()
    {

        $id = $this->getRequest()->getParam('tag_id');

        if ($id) {
            $this->model->load($id);
            if (!$this->model->getId()) {
                $this->messageManager
                ->addError(__('This tag no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('StorePickup_tags', $this->model);

        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(
            $id ? __('Edit Tags') : __('New Tag'),
            $id ? __('Edit Tags') : __('New Tag')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Tags'));
        $resultPage->getConfig()->getTitle()
            ->prepend($this->model->getId() ? $this->model->getTagName() : __('New Tags'));

        return $resultPage;
    }
}
