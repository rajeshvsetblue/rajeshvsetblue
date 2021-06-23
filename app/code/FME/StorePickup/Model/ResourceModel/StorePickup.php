<?php

namespace FME\StorePickup\Model\ResourceModel;

use FME\StorePickup\Model\ResourceModel\Tags;
use Magento\Framework\Model\AbstractModel;

class StorePickup extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_tagCollectionFactory;
    protected $_tagGmapTable;

    protected function _construct()
    {
        $this->_init('fme_storepickup', 'gmaps_id');
    }
    /**
     * Save data related with storelocator
     *
     * @param \Magento\Framework\DataObject $product
     * @return $this
     */
    protected function _afterSave(AbstractModel $product)
    {

        $this->_saveGmapsTags($product);
        $this->_saveGmapsHoliday($product);
        $this->_saveGmapsProducts($product);
        return parent::_afterSave($product);
    }
    public function _beforeSave(AbstractModel $object)
    {
        
        if (!$this->isValidPageIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The page URL key contains capital letters or disallowed symbols.')
            );
        }

        if ($this->isNumericPageIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The page URL key cannot be made of only numbers.')
            );
        }

        if (!$this->getIsUniqueStoreUrlTo($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('URL key already exists.')
            );
        }

        return parent::_beforeSave($object);
    }
    public function isNumericPageIdentifier(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }
    public function isValidPageIdentifier(AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }
    public function getIsUniqueStoreUrlTo(\Magento\Framework\Model\AbstractModel $object)
    {
        
        $select = $this->getConnection()->select()->from(
            ['ft' => $this->getMainTable()]
        )->where(
            'ft.identifier = ?',
            $object->getData('identifier')
        );

        if ($object->getId()) {
            $select->where('ft.gmaps_id <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    protected function _saveGmapsTags(\Magento\Framework\Model\AbstractModel $object)
    {
        $tagIds = $object->getData('tag_id');
   
        if (isset($tagIds)) {
            $productIds = $tagIds;
            $condition = $this->getConnection()->quoteInto('gmaps_id = ?', $object->getId());
            $this->getConnection()->delete($this->getTable('fme_storepickup_related_tags'), $condition);
            foreach ($productIds as $_product) {
                if($_product):
                    $gTagsArray = [];
                    $gTagsArray['gmaps_id'] = $object->getId();
                    $gTagsArray['tag_id'] = $_product;
                    $this->getConnection()->insert($this->getTable('fme_storepickup_related_tags'), $gTagsArray);
                endif;
            }
        }
        return $this;
    }
    protected function _saveGmapsHoliday(\Magento\Framework\Model\AbstractModel $object)
    {
        $tagIds = $object->getData('holiday_id');
        if (isset($tagIds)) {
            $productIds = $tagIds;
            $condition = $this->getConnection()->quoteInto('gmaps_id = ?', $object->getId());
            $this->getConnection()->delete($this->getTable('fme_storepickup_related_holiday'), $condition);
            foreach ($productIds as $_product) {
                $gTagsArray = [];
                $gTagsArray['gmaps_id'] = $object->getId();
                $gTagsArray['holiday_id'] = $_product;
                $this->getConnection()->insert($this->getTable('fme_storepickup_related_holiday'), $gTagsArray);
            }
        }
        return $this;
    }
    protected function _saveGmapsProducts(\Magento\Framework\Model\AbstractModel $object)
    {
            $relatedPids = $object->getData('entity_id');
        if (isset($relatedPids)) {
            $condition = $this->getConnection()->quoteInto(
                'gmaps_id = ?',
                $object->getId()
            );
            $this->getConnection()->delete(

                $this->getTable('fme_storepickup_products'),
                $condition
            );

            foreach ($relatedPids as $rPids) {
                $gProdArray = [];
                $gProdArray['gmaps_id']  = $object->getId();
                $gProdArray['entity_id'] = $rPids;

                $this->getConnection()->insert($this->getTable('fme_storepickup_products'), $gProdArray);
            }
        }
    }
    public function checkUrlKey($identifier, $storeId)
    {
        $main = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        $select = $this->getConnection()->select()->from(
            [$this->getMainTable()]
        )->where(
            'identifier = ?',
            $identifier
        )->where('CONCAT(",", `store_id`, ",") REGEXP ",('.$main.'|'.$storeId.'),"');
        $active = 1;
        $select->where('is_active = ?', $active);
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)->columns('gmaps_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
