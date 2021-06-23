<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace FME\StorePickup\Block\Adminhtml\Storelocator\Edit;

class AssignMap extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'FME_StorePickup::storelocator/mapconfig.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;
    protected $_productFactory;
    protected $_storeFactory;
    public $_storeAdminHelper;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
         \FME\StorePickup\Helper\Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \FME\StorePickup\Model\StorePickup $storeFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        
        $this->_storeFactory = $storeFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_storeAdminHelper = $helper;
        parent::__construct($context);
    }
    
    public function getBlockGrid()
    {        
       $id = $this->getRequest()->getParam('gmaps_id');
       $mediaobj = $this->_storeFactory->getStores($id);
     return $mediaobj;
    }
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }   

}
