<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel\StorePickup;

use \FME\StorePickup\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'gmaps_id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            
            'FME\StorePickup\Model\StorePickup',
            'FME\StorePickup\Model\ResourceModel\StorePickup'
        );
        $this->_map['fields']['gmaps_id'] = 'main_table.gmaps_id';
    }
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }
}
