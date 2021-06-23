<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\Holiday\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */

class IsActive implements OptionSourceInterface
{
    protected $googleHoliday;
  
    public function __construct(\FME\StorePickup\Model\Holiday $googleHoliday)
    {
        $this->googleHoliday = $googleHoliday;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->googleHoliday->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
