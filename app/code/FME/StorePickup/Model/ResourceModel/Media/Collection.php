<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel\Media;

use \FME\StorePickup\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'media_id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            
            'FME\StorePickup\Model\Media',
            'FME\StorePickup\Model\ResourceModel\Media'
        );
        $this->_map['fields']['media_id'] = 'main_table.media_id';
    }
}
