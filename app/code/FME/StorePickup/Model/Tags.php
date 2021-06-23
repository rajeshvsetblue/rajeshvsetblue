<?php

namespace FME\StorePickup\Model;

class Tags extends \Magento\Framework\Model\AbstractModel
{
        const STATUS_ENABLED = 1;
        const STATUS_DISABLED = 0;
        
    protected function _construct()
    {
        $this->_init('FME\StorePickup\Model\ResourceModel\Tags');
    }
    public function checkUrlKey($identifier)
    {
        return $this->_getResource()->checkUrlKey($identifier);
    }
    public function getTags($id)
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup_related_tags'))->where('tag_id = ?', $id);
        $data = $this->_getResource()->getConnection()->fetchAll($select);
        if ($data) {
            $productsArr = [];
            foreach ($data as $_i) {
                $productsArr[] = $_i['gmaps_id'];
            }
            return $productsArr;
        }
    }
}
