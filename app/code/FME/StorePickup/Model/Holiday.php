<?php

namespace FME\StorePickup\Model;

class Holiday extends \Magento\Framework\Model\AbstractModel
{
        const STATUS_ENABLED = 1;
        const STATUS_DISABLED = 0;
        
    protected function _construct()
    {
        $this->_init('FME\StorePickup\Model\ResourceModel\Holiday');
    }
    public function getAvailableStatuses()
    {
        $availableOptions = ['1' => 'Enable',
                           '0' => 'Disable'];
        return $availableOptions;
    }
    public function getSpecialStatuses()
    {
        $availableOptions = ['1' => 'Yes',
                           '0' => 'No'];
        return $availableOptions;
    }
}
