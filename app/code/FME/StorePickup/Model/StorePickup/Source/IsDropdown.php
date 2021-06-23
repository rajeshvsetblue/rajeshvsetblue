<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\StorePickup\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */

class IsDropdown implements OptionSourceInterface
{
    protected $googleStore;
  
    public function __construct(\FME\StorePickup\Model\StorePickup $googleStore)
    {
        $this->googleStore = $googleStore;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->googleStore->getTimetableCollection();
        $options = [];
        $options[] = [
                        'label' => 'Select Timings',
                        'value' => '0'
                        ];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value['timetable_name'],
                'value' => $value['timetable_id'],
            ];
        }
        return $options;
    }
}
