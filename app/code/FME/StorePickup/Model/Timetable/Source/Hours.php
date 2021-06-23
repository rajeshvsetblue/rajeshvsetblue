<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\Timetable\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */

class Hours implements OptionSourceInterface
{
    protected $googleTimetable;
  
    public function __construct(\FME\StorePickup\Model\Timetable $googleTimetable)
    {
        $this->googleTimetable = $googleTimetable;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->googleTimetable->getHours();
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
