<?php

namespace FME\StorePickup\Model\ResourceModel;

use FME\StorePickup\Model\ResourceModel\Tags;
use Magento\Framework\Model\AbstractModel;

class Tags extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('fme_storepickup_tags', 'tag_id');
    }

    protected function _afterSave(AbstractModel $product)
    {

      //  $this->_saveGmapsTagsKey($product);
        return parent::_afterSave($product);
    }

    protected function _saveGmapsTagsKey(\Magento\Framework\Model\AbstractModel $object)
    {
        $tagIds = $object->getData('tag_id');
        if (isset($tagIds)) {
            $productIds = $tagIds;
            $condition = $this->getConnection()->quoteInto('tag_id = ?', $object->getId());
            
            $tagval = 'tag_'.$object->getId();
            $data = ['hu' => $tagval];
            
             $this->getConnection()->update(
                 $this->getTable('fme_storepickup_tags'),
                 $data,
                 $condition
             );
        }
        return $this;
    }
    public function checkUrlKey($identifier)
    {
        $select = $this->getConnection()->select()->from(
            [$this->getMainTable()]
        )->where(
            'tag_name = ?',
            $identifier
        );
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)->columns('tag_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
