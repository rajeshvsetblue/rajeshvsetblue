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

class IsActive implements OptionSourceInterface
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
        $availableOptions = $this->googleStore->getAvailableStatuses();
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
