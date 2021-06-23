<?php
namespace FME\StorePickup\Controller\Adminhtml\Tags;

abstract class Tags extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'FME_StorePickup::item_list';
}
