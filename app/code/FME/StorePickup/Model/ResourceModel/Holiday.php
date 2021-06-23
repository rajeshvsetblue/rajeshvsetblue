<?php

namespace FME\StorePickup\Model\ResourceModel;

class Holiday extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('fme_storepickup_holiday', 'holiday_id');
    }
}
