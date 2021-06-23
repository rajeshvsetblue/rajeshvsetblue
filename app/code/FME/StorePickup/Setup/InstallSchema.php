<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace FME\StorePickup\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
//use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'fme_StorePickup'
         */
         $tableName = $installer->getTable('fme_storepickup');
           if ($installer->getConnection()->isTableExists($tableName) != true) {


        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup')
        )->addColumn(
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Gmaps ID'
        )->addColumn(
            'timetable_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => true],
            'Timetable ID'
        )->addColumn(
            'store_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Name'
        )->addColumn(
            'store_phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Phone'
        )->addColumn(
            'store_fax',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Fax'
        )->addColumn(
            'region',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store region'
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store region Id'
        )->addColumn(
            'postcode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Postocde'
        )->addColumn(
            'city',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store city'
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Country'
        )->addColumn(
            'address',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Store Address'
        )->addColumn(
            'identifier',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Block String Identifier'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store Selected'
        )->addColumn(
            'store_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Store Description'
        )->addColumn(
            'meta_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Meta Description'
        )->addColumn(
            'meta_keyword',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Meta Keywords'
        )->addColumn(
            'latitude',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Latitude'
        )->addColumn(
            'longitude',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Longitude'
        )->addColumn(
            'zoom_level',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Zoom Level'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Store Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Store Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Store Active'
        )->addIndex(
                            $installer->getIdxName(
                                    'fme_storepickup',['store_name', 'identifier']),['store_name', 'identifier']
                    )->setComment('FME Stores Table');
                  //  ->setOption('type', 'InnoDB')
                //    ->setOption('charset', 'utf8');









        //
        // ->addIndex(
        //     $setup->getIdxName(
        //         $installer->getTable('fme_storepickup'),
        //         ['store_name', 'identifier', 'store_description'],
        //         \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT
        //     ),
        //     ['store_name', 'identifier', 'store_description'],
        //     ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT]
        // )->setComment(
        //     'FME Stores Table'
        // );
        $installer->getConnection()->createTable($table);
      }
        /**
         * Create table 'fme_tags'
         */
         $tableName = $installer->getTable('fme_storepickup_tags');
           if ($installer->getConnection()->isTableExists($tableName) != true) {


        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_tags')
        )->addColumn(
            'tag_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Tag ID'
        )->addColumn(
            'tag_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tag Name'
        )->addColumn(
            'tag_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Tag Description'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tag Icon'
        )->addColumn(
            'path',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Image Path'
        )->addIndex(
            $installer->getIdxName('fme_storepickup_tags', ['tag_id']),
            ['tag_id']
        );
        $installer->getConnection()->createTable($table);
      }
        /**
         * Create table 'fme_gmaps_tags'
         */
         $tableName = $installer->getTable('fme_storepickup_related_tags');
           if ($installer->getConnection()->isTableExists($tableName) != true) {

        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_related_tags')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Gmap Tag ID'
        )->addColumn(
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Gmaps ID'
        )->addColumn(
            'tag_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'primary' => true],
            'Tag ID'
        )->addForeignKey(
            $installer->getFkName('fme_storepickup_related_tags', 'gmaps_id', 'fme_storepickup', 'gmaps_id'),
            'gmaps_id',
            $installer->getTable('fme_storepickup'),
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        // ->addForeignKey(
        //     $installer->getFkName('fme_gmaps_tags', 'tag_id', 'fme_tags', 'hu'),
        //     'tag_id',
        //     $installer->getTable('fme_tags'),
        //     'hu',
        //     \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        // );
        $installer->getConnection()->createTable($table);
      }
        // Holidays
        /**
         * Create table 'fme_holiday'
         */
         $tableName = $installer->getTable('fme_storepickup_holiday');
           if ($installer->getConnection()->isTableExists($tableName) != true) {


        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_holiday')
        )->addColumn(
            'holiday_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Holiday ID'
        )->addColumn(
            'holiday_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Holiday Name'
        )->addColumn(
            'holiday_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Holiday Description'
        )->addColumn(
            'holiday_start_from',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Holiday Start Time'
        )->addColumn(
            'holiday_ends_to',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Holiday End Time'
        )->addColumn(
            'is_special',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Holiday Special'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Holiday Active'
        )->addIndex(
            $installer->getIdxName('fme_storepickup_holiday', ['holiday_id']),
            ['holiday_id']
        );
        $installer->getConnection()->createTable($table);
      }
        /**
         * Create table 'fme_gmaps_holiday'
         */
         $tableName = $installer->getTable('fme_storepickup_related_holiday');
           if ($installer->getConnection()->isTableExists($tableName) != true) {

        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_related_holiday')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Gmap Holiday ID'
        )->addColumn(
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Gmaps ID'
        )->addColumn(
            'holiday_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Holiday ID'
        )->addForeignKey(
            $installer->getFkName('fme_storepickup_related_holiday', 'gmaps_id', 'fme_storepickup', 'gmaps_id'),
            'gmaps_id',
            $installer->getTable('fme_storepickup'),
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('fme_storepickup_related_holiday', 'holiday_id', 'fme_storepickup_holiday', 'holiday_id'),
            'holiday_id',
            $installer->getTable('fme_storepickup_holiday'),
            'holiday_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);
      }
        // Holidays
        /**
         * Create table 'fme_timetable'
         */
         $tableName = $installer->getTable('fme_storepickup_timetable');
           if ($installer->getConnection()->isTableExists($tableName) != true) {


        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_timetable')
        )->addColumn(
            'timetable_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Timetable ID'
        )->addColumn(
            'timetable_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Timetable Name'
        )->addColumn(
            'monday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Monday Status'
        )->addColumn(
            'monday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Monday Open Time'
        )->addColumn(
            'monday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Monday Close Time'
        )->addColumn(
            'monday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Monday Break Time'
        )->addColumn(
            'monday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Monday End Break Time'
        )->addColumn(
            'tuesday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tuesday Status'
        )->addColumn(
            'tuesday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tuesday Open Time'
        )->addColumn(
            'tuesday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tuesday Close Time'
        )->addColumn(
            'tuesday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tuesday Break Time'
        )->addColumn(
            'tuesday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Tuesday End Break Time'
        )->addColumn(
            'wednesday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Wednesday Status'
        )->addColumn(
            'wednesday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Wednesday Open Time'
        )->addColumn(
            'wednesday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Wednesday Close Time'
        )->addColumn(
            'wednesday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Wednesday Break Time'
        )->addColumn(
            'wednesday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Wednesday End Break Time'
        )->addColumn(
            'thursday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thursday Status'
        )->addColumn(
            'thursday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thursday Open Time'
        )->addColumn(
            'thursday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thursday Close Time'
        )->addColumn(
            'thursday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thursday Break Time'
        )->addColumn(
            'thursday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thursday End Break Time'
        )->addColumn(
            'friday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Friday Status'
        )->addColumn(
            'friday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Friday Open Time'
        )->addColumn(
            'friday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Friday Close Time'
        )->addColumn(
            'friday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Friday Break Time'
        )->addColumn(
            'friday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Firday End Break Time'
        )->addColumn(
            'saturday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Saturday Status'
        )->addColumn(
            'saturday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Saturday Open Time'
        )->addColumn(
            'saturday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Saturday Close Time'
        )->addColumn(
            'saturday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Saturday Break Time'
        )->addColumn(
            'saturday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Saturday End Break Time'
        )->addColumn(
            'sunday_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Sunday Status'
        )->addColumn(
            'sunday_open_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Sunday Open Time'
        )->addColumn(
            'sunday_close_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Sunday Close Time'
        )->addColumn(
            'sunday_break_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Sunday Break Time'
        )->addColumn(
            'sunday_offbreak_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Sunday End Break Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Timetable Active'
        )->addIndex(
            $installer->getIdxName('fme_storepickup_timetable', ['timetable_id']),
            ['timetable_id']
        );
        $installer->getConnection()->createTable($table);
      }
        /**
         * Create table 'fme_storepickup_products'
         */
         $tableName = $installer->getTable('fme_storepickup_products');
           if ($installer->getConnection()->isTableExists($tableName) != true) {

        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_storepickup_products')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Gmaps ID'
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'primary' => true],
            'Product ID'
        )->addForeignKey(
            $installer->getFkName('fme_storepickup_products', 'gmaps_id', 'fme_storepickup', 'gmaps_id'),
            'gmaps_id',
            $installer->getTable('fme_storepickup'),
            'gmaps_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);




        $installer->getConnection()->addColumn(
    $installer->getTable('quote'),
    'fmestorepickup_id',
    [
        'type' => 'text',
        'nullable' => false,
        'comment' => 'StorePickup Id',
    ]
);
$installer->getConnection()->addColumn(
    $installer->getTable('quote'),
    'fmepickup_datetime',
    [
        'type' => 'datetime',
        'nullable' => false,
        'comment' => 'Store Pickup Data and Time',
    ]
);

$installer->getConnection()->addColumn(
    $installer->getTable('sales_order'),
    'fmestorepickup_id',
    [
        'type' => 'text',
        'nullable' => true,
        'comment' => 'StorePickup Id',
    ]
);
$installer->getConnection()->addColumn(
    $installer->getTable('sales_order'),
    'fmepickup_datetime',
    [
        'type' => 'datetime',
        'nullable' => true,
        'comment' => 'Store Pickup Data and Time',
    ]
);




}


        $installer->endSetup();
    }
}
