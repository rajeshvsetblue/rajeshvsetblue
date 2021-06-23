<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Model\ResourceModel;

use Magento\Framework\DB\Select;

abstract class AbstractCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }
    public function addAttributeToSelect($attribute)
    {
        $this->addFieldToSelect($this->_attributeToField($attribute));
        return $this;
    }
    protected function _attributeToField($attribute)
    {
        $field = false;
        if (is_string($attribute)) {
            $field = $attribute;
        } elseif ($attribute instanceof \Magento\Eav\Model\Entity\Attribute) {
            $field = $attribute->getAttributeCode();
        }
        if (!$field) {
            throw new \Magento\Framework\Exception\LocalizedException(__('We cannot determine the field name.'));
        }
        return $field;
    }
    public function joinField($alias, $table, $field, $bind, $cond = null, $joinType = 'inner')
    {
        // validate alias
        if (isset($this->_joinFields[$alias])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('A joined field with this alias is already declared.'));
        }

        $table = $this->_resource->getTableName($table);
        $tableAlias = $this->_getAttributeTableAlias($alias);

        // validate bind
        list($pKey, $fKey) = explode('=', $bind);
        $pKey = $this->getSelect()->getConnection()->quoteColumnAs(trim($pKey), null);
        $bindCond = $tableAlias . '.' . trim($pKey) . '=' . $this->_getAttributeFieldName(trim($fKey));

        // process join type
        switch ($joinType) {
            case 'left':
                $joinMethod = 'joinLeft';
                break;
            default:
                $joinMethod = 'join';
                break;
        }
        $condArr = [$bindCond];

        // add where condition if needed
        if ($cond !== null) {
            if (is_array($cond)) {
                foreach ($cond as $key => $value) {
                    $condArr[] = $this->_getConditionSql($tableAlias . '.' . $key, $value);
                }
            } else {
                $condArr[] = str_replace('{{table}}', $tableAlias, $cond);
            }
        }
        $cond = '(' . implode(') AND (', $condArr) . ')';
        $this->getSelect()->{$joinMethod}(
            [$tableAlias => $table],
            $cond,
            $field ? [$alias => $field] : []
        );
        $this->_joinFields[$alias] = ['table' => $tableAlias, 'field' => $field];

        return $this;
    }
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store_id', ['in' => $store], 'public');
    }
}
