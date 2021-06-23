<?php

namespace FME\StorePickup\Model;

class StorePickup extends \Magento\Framework\Model\AbstractModel
{
        const STATUS_ENABLED = 1;
        const STATUS_DISABLED = 0;
    protected $_logger;
    protected function _construct()
    {
        $this->_init('FME\StorePickup\Model\ResourceModel\StorePickup');
    }
    public function getAvailableStatuses()
    {
        $availableOptions = ['1' => 'Enable',
                           '0' => 'Disable'];
        return $availableOptions;
    }
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return [];
        }
        $array = $this->getData('products_position');
    

        if ($array === null) {
            $temp = $this->getData('gmaps_id');
            $tagsname = $this->getRelatedProducts($temp);

            if($tagsname !== null){
                for ($i = 0; $i < sizeof($tagsname); $i++) {
                    $array[$tagsname[$i]] = 0;
                }
            }
        
            $this->setData('products_position', $array);
        }
        return $array;
    }
    
    public function getTagsPosition()
    {
        if (!$this->getId()) {
            return [];
        }
        $array = $this->getData('tags_position');
        if ($array === null) {
            $temp = $this->getData('gmaps_id');
            $tagsname = $this->getTags($temp);
            if($tagsname !== null):
                for ($i = 0; $i < sizeof($tagsname); $i++) {
                    $array[$tagsname[$i]] = 0;
                }
            endif;
            $this->setData('tags_position', $array);
        }
        return $array;
    }
    public function getHolidayPosition()
    {
        if (!$this->getId()) {
            return [];
        }
        $array = $this->getData('holiday_position');
        if ($array === null) {
            $temp = $this->getData('gmaps_id');
            $tagsname = $this->getHoliday($temp);

            if($tagsname !== null){
                for ($i = 0; $i < sizeof($tagsname); $i++) {
                    $array[$tagsname[$i]] = 0;
                }
            }
        
            $this->setData('holiday_position', $array);
        }
        return $array;
    }
    
    public function getTags($id)
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup_related_tags'))->where('gmaps_id = ?', $id);
        $data = $this->_getResource()->getConnection()->fetchAll($select);
        if ($data) {
            $productsArr = [];
            foreach ($data as $_i) {
                $productsArr[] = $_i['tag_id'];
            }
            return $productsArr;
        }
    }
    public function getHoliday($id)
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup_related_holiday'))->where('gmaps_id = ?', $id);
        $data = $this->_getResource()->getConnection()->fetchAll($select);
        if ($data) {
            $productsArr = [];
            foreach ($data as $_i) {
                $productsArr[] = $_i['holiday_id'];
            }
            return $productsArr;
        }
    }

    public function getTimetableCollection()
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup_timetable'), ['timetable_id', 'timetable_name'])
        ->where('is_active = ?', '1');
        $data = $this->_getResource()->getConnection()
          ->fetchAll($select);
          return $data;
    }
    public function getRelatedProducts($id)
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup_products'))->where('gmaps_id = ?', $id);
        $data = $this->_getResource()->getConnection()->fetchAll($select);
        if ($data) {
            $productsArr = [];
            foreach ($data as $_i) {
                $productsArr[] = $_i['entity_id'];
            }
            return $productsArr;
        }
    }

    public function getStores($id)
    {
        $select = $this->_getResource()->getConnection()->select()->from($this->_getResource()->getTable('fme_storepickup'))->where('gmaps_id = ?', $id);
        $data = $this->_getResource()->getConnection()->fetchAll($select);
        if ($data) {
            
            return $data;
        }
    }
    public function checkUrlKey($identifier, $storeId)
    {
        return $this->_getResource()->checkUrlKey($identifier, $storeId);
    }
    
    public function getProductAtStores($product_sku)
    {
        
        $collection = $this->getCollection();
        
        $collection->getSelect()->join(
            ['productStore' => $this->_getResource()->getTable('fme_storepickup_products')],
            'productStore.gmaps_id = main_table.gmaps_id',
            []
        )->where(
            'productStore.entity_id =?', $product_sku
        );
        
        return $collection;
    }
    
}
