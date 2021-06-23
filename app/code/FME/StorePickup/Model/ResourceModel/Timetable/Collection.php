<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel\Timetable;

use \FME\StorePickup\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'timetable_id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            
            'FME\StorePickup\Model\Timetable',
            'FME\StorePickup\Model\ResourceModel\Timetable'
        );
        $this->_map['fields']['timetable_id'] = 'main_table.timetable_id';
    }
}
