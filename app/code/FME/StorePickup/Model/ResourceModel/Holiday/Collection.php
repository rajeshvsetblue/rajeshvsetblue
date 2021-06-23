<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel\Holiday;

use \FME\StorePickup\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'holiday_id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            
            'FME\StorePickup\Model\Holiday',
            'FME\StorePickup\Model\ResourceModel\Holiday'
        );
        $this->_map['fields']['holiday_id'] = 'main_table.holiday_id';
    }
}
