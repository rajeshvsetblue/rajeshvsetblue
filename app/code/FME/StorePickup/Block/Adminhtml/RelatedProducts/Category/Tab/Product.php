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
namespace FME\StorePickup\Block\Adminhtml\RelatedProducts\Category\Tab;

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
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \FME\StorePickup\Model\StorePickup $storeFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_storeFactory = $storeFactory;
        $this->productVisibility = $productVisibility;             
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('catalog_category_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('gmaps_id')) {
            $this->setDefaultFilter(['in_gmaps'=>1]);
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
                $this->getCollection()->addFieldToFilter('sku', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('sku', ['nin' => $productIds]);
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
               $collection = $this->_productFactory->create()->getCollection();
               $collection->setVisibility($this->productVisibility->getVisibleInSiteIds())
               ->addAttributeToSelect(
                   'name'
               )->addAttributeToSelect(
                   'sku'
               )->addAttributeToSelect(
                   'price'
               )
        ->addOrder('entity_id', 'asc');
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
                    'header_css_class'  => 'a-center',
                    'type' => 'checkbox',
                    'name' => 'in_gmaps',
                    'values' => $this->_getSelectedProducts(),
                    'index' => 'sku',
                    'use_index' => true,
                     // 'header_css_class' => 'col-select col-massaction',
                     // 'column_css_class' => 'col-select col-massaction'
                ]
            );
       
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price'
            ]
        );
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
        return $this->getUrl('storepickup/*/gridp', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $id = $this->getRequest()->getParam('gmaps_id');
        $relatedProducts   = $this->_storeFactory->getRelatedProducts($id);
        if ($relatedProducts) {
            $relatedProducts = array_values($relatedProducts);
            return $relatedProducts;
        }
    }
}
