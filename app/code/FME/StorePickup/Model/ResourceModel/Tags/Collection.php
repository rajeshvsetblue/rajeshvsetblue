<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel\Tags;

use \FME\StorePickup\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'tag_id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            
            'FME\StorePickup\Model\Tags',
            'FME\StorePickup\Model\ResourceModel\Tags'
        );
        $this->_map['fields']['tag_id'] = 'main_table.tag_id';
    }
}
