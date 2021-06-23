<?php

namespace FME\StorePickup\Model;

class Timetable extends \Magento\Framework\Model\AbstractModel
{
        const STATUS_ENABLED = 1;
        const STATUS_DISABLED = 0;
        
    protected function _construct()
    {
        $this->_init('FME\StorePickup\Model\ResourceModel\Timetable');
    }
    public function getAvailableStatuses()
    {
        $availableOptions = ['1' => 'Enable',
                           '0' => 'Disable'];
        return $availableOptions;
    }
    public function getSpecialStatuses()
    {
        $availableOptions = ['0' => 'Close',
                           '1' => 'Open'];
        return $availableOptions;
    }
    
    public function getMinutes()
    {
        $availableOptions = [
                                '0' => 'Close',
                                '1' => 'Open',
                            ];
        return $availableOptions;
    }
}
