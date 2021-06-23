<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), '1.0.9', '<')) {
            $this->addIndexToStorePickup($setup);
            $this->addIndexToTags($setup);
            $this->addIndexToHoliday($setup);
            $this->addIndexToTimeTable($setup);            
        }
        
        $setup->endSetup();
    }

    
    protected function addIndexToStorePickup(SchemaSetupInterface $setup)
    {
        
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('fme_storepickup');
        
        $keyName = $setup->getIdxName(
            $tableName,
            ['store_name', 'address', 'store_description', 'meta_description', 'meta_keyword'], 
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        
        $existingKeys = $connection->getIndexList($tableName);
        
        if (!array_key_exists($keyName, $existingKeys)) {
            
                $connection->addIndex(  
                      $tableName,  
                      $keyName,
                      ['store_name', 'address', 'store_description', 'meta_description', 'meta_keyword'], 
                      \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
        }        
        
    }
    
    protected function addIndexToTags(SchemaSetupInterface $setup)
    {
        
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('fme_storepickup_tags');
        
        $keyName = $setup->getIdxName(
            $tableName,
            ['tag_name', 'tag_description'], 
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        
        $existingKeys = $connection->getIndexList($tableName);
        
        if (!array_key_exists($keyName, $existingKeys)) {
            
                $connection->addIndex(  
                      $tableName,  
                      $keyName,
                      ['tag_name', 'tag_description'],
                      \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
        }        
    }
    
    
    protected function addIndexToHoliday(SchemaSetupInterface $setup)
    {
        
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('fme_storepickup_holiday');
        
        $keyName = $setup->getIdxName(
            $tableName,
            ['holiday_name', 'holiday_description'], 
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        
        $existingKeys = $connection->getIndexList($tableName);
        
        if (!array_key_exists($keyName, $existingKeys)) {
            
                $connection->addIndex(  
                      $tableName,  
                      $keyName,
                      ['holiday_name', 'holiday_description'], 
                      \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
        }        
    }
    
    
    protected function addIndexToTimeTable(SchemaSetupInterface $setup)
    {
        
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('fme_storepickup_timetable');
        
        $keyName = $setup->getIdxName(
            $tableName,
            ['timetable_name'], 
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        
        $existingKeys = $connection->getIndexList($tableName);
        
        if (!array_key_exists($keyName, $existingKeys)) {
            
                $connection->addIndex(  
                      $tableName,  
                      $keyName,
                      ['timetable_name'],
                      \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
        }        
    }
    
    
    
}
