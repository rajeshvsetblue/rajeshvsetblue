<?php

namespace FME\StorePickup\Model\ResourceModel;

class Media extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('fme_media', 'media_id');
    }
}
