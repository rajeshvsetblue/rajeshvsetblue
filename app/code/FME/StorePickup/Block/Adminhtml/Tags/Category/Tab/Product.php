<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace FME\StorePickup\Block\Adminhtml\Tags\Category\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class Product extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    protected $_storeFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \FME\StorePickup\Model\TagsFactory $productFactory,
        \FME\StorePickup\Model\StorePickup $storeFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_storeFactory = $storeFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('catalog_category_tags');
       
        $this->setDefaultSort('tag_id');
        $this->setUseAjax(true);

        
        if ($this->getRequest()->getParam('gmaps_id')) {
            
            $tagIds = $this->_getSelectedProducts();
            if (!empty($tagIds)) {
                
                $this->setDefaultFilter(['in_gmaps'=>1]);
            }
        }
    }

    /**
     * @return array|null
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('storepickup_storelocator');
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
       
        if ($column->getId() == 'in_gmaps') {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('tag_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('tag_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
               $collection = $this->_productFactory->create()->getCollection()
                          ->addOrder('tag_id', 'asc');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        
            $this->addColumn(
                'in_gmaps',
                [
                    'header_css_class' => 'col-select col-massaction',
                    'column_css_class' => 'col-select col-massaction',
                    'type' => 'checkbox',
                    'name' => 'in_gmaps',
                    'values' => $this->_getSelectedProducts(),
                    'index' => 'tag_id',
                    'use_index' => true
                    
                ]
            );
        $this->addColumn(
            'tag_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'tag_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        
        $this->addColumn('tag_name', ['header' => __('Tag Name'), 'index' => 'tag_name']);
        $this->addColumn('tag_description', ['header' => __('Tag Description'), 'index' => 'tag_description']);
        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'type' => 'number',
                'index' => 'position',
                'editable' => 'false'
            ]
        );
        
        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('storepickup/*/grid', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $id = $this->getRequest()->getParam('gmaps_id');
        $mediaobj   = $this->_storeFactory->getTags($id);
        if ($mediaobj) {
            $mediaobj = array_values($mediaobj);
            return $mediaobj;
        }
    }
}
