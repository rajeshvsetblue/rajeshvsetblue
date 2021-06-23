<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\Tags\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */

class IsActive implements OptionSourceInterface
{
    protected $googleStore;
  
    public function __construct(\FME\StorePickup\Model\Tags $googleTags)
    {
        $this->googleTags = $googleTags;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->googleTags->getAvailableStatuses();
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
