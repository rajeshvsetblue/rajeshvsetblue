<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Storelocator;

class Gridp extends \Magento\Catalog\Controller\Adminhtml\Category
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $category = $this->_initCategory(true);
        if (!$category) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('StorePickup/*/', ['_current' => true, 'id' => null]);
        }
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'FME\StorePickup\Block\Adminhtml\RelatedProducts\Category\Tab\Product',
                'related.product.grid'
            )->toHtml()
        );
    }
}
