<?php

namespace FME\StorePickup\Model\ResourceModel;

class Timetable extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('fme_storepickup_timetable', 'timetable_id');
    }
}
