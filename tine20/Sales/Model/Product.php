<?php

/**
 * Tine 2.0
 * 
 * @package     Sales
 * @subpackage  Product
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2021 Metaways Infosystems GmbH (http://www.metaways.de)
 */

/**
 * class to hold product data
 * 
 * @package     Sales
 * @subpackage  Product
 */
class Sales_Model_Product extends Tinebase_Record_NewAbstract
{
    public const FLD_ACCOUNTABLE = 'accountable';
    public const FLD_CATEGORY = 'category';
    public const FLD_COSTCENTER = 'costcenter';
    public const FLD_DESCRIPTION = 'description';
    public const FLD_DEFAULT_GROUPING = 'default_grouping';
    public const FLD_DEFAULT_SORT = 'default_sort';
    public const FLD_GTIN = 'gtin';
    public const FLD_IS_ACTIVE = 'is_active';
    public const FLD_IS_SALESPRODUCT = 'is_salesproduct';
    public const FLD_LIFESPAN_END = 'lifespan_end';
    public const FLD_LIFESPAN_START = 'lifespan_start';
    public const FLD_MANUFACTURER = 'manufacturer';
    public const FLD_NAME = 'name';
    public const FLD_NUMBER = 'number';
    public const FLD_PURCHASEPRICE = 'purchaseprice';
    public const FLD_SALESPRICE = 'salesprice';
    public const FLD_SALESTAX = 'salestax';
    public const FLD_SHORTCUT = 'shortcut';
    public const FLD_SUBPRODUCTS = 'subproducts'; // -> recordset of Sales_Model_SubProduct dependent records
    public const FLD_UNFOLD_TYPE = 'unfold_type'; // -> keyfield (Bundle, Set, leer)
    public const FLD_UNIT = 'unit'; // -> keyfield

    public const MODEL_NAME_PART = 'Product';
    public const TABLE_NAME = 'sales_products';

    /**
     * Holds the model configuration (must be assigned in the concrete class)
     *
     * @var array
     */
    protected static $_modelConfiguration = [
        self::VERSION => 8,
        self::MODLOG_ACTIVE => true,

        self::APP_NAME => Sales_Config::APP_NAME,
        self::MODEL_NAME => self::MODEL_NAME_PART,

        self::RECORD_NAME => 'Product',
        self::RECORDS_NAME => 'Products', // ngettext('Product', 'Products', n)
        self::TITLE_PROPERTY => self::FLD_NAME,

        self::HAS_ATTACHMENTS => true,
        self::HAS_CUSTOM_FIELDS => true,
        self::HAS_NOTES => false,
        self::HAS_RELATIONS => true,
        self::HAS_TAGS => true,

        self::EXPOSE_HTTP_API => true,
        self::EXPOSE_JSON_API => true,
        self::CREATE_MODULE => true,

        self::DEFAULT_SORT_INFO => ['field' => 'number', 'direction' => 'DESC'],

        self::TABLE => [
            self::NAME => self::TABLE_NAME,
            self::INDEXES       => [
                self::FLD_DESCRIPTION => [
                    self::COLUMNS               => [self::FLD_DESCRIPTION],
                    self::FLAGS                 => [self::TYPE_FULLTEXT],
                ],
            ],
        ],

        self::ASSOCIATIONS              => [
            \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE => [
                self::FLD_COSTCENTER        => [
                    self::TARGET_ENTITY         => Sales_Model_CostCenter::class,
                    self::FIELD_NAME            => self::FLD_COSTCENTER,
                    self::JOIN_COLUMNS          => [[
                        self::NAME                  => self::FLD_COSTCENTER,
                        self::REFERENCED_COLUMN_NAME=> 'id',
                    ]],
                ],
            ],
        ],

        self::FIELDS => [
            self::FLD_NUMBER => [
                self::TYPE => self::TYPE_STRING,
                self::QUERY_FILTER => true,
                self::LABEL => 'Number', // _('Number')
                self::LENGTH => 64,
            ],
            self::FLD_NAME => [
                self::TYPE => self::TYPE_STRING,
                self::QUERY_FILTER => true,
                self::LABEL => 'Name', // _('Name')
                self::LENGTH => 255,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => false,
                    Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
                ]
            ],
            self::FLD_DESCRIPTION => [
                self::TYPE => self::TYPE_FULLTEXT,
                self::QUERY_FILTER => true,
                self::LABEL => 'Description', // _('Description')
                self::NULLABLE => true,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            self::FLD_PURCHASEPRICE => [
                self::TYPE => self::TYPE_MONEY,
                self::LABEL => 'Purchaseprice', // _('Purchaseprice')
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => false,
                    Zend_Filter_Input::DEFAULT_VALUE => 0
                ],
                self::DEFAULT_VAL => 0,
                self::INPUT_FILTERS => [Zend_Filter_Empty::class => 0]
            ],
            self::FLD_SALESPRICE => [
                self::TYPE => self::TYPE_MONEY,
                self::LABEL => 'Salesprice', // _('Salesprice')
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => false,
                    Zend_Filter_Input::DEFAULT_VALUE => 0
                ],
                self::DEFAULT_VAL => 0,
                self::INPUT_FILTERS => [Zend_Filter_Empty::class => 0]
            ],
            self::FLD_CATEGORY => [
                self::TYPE => self::TYPE_KEY_FIELD,
                self::LABEL => 'Category', // _('Category')
                self::DEFAULT_VAL => 'DEFAULT',
                self::NAME => Sales_Config::PRODUCT_CATEGORY,
                self::NULLABLE => true,
            ],
            self::FLD_MANUFACTURER => [
                self::TYPE => self::TYPE_STRING,
                self::QUERY_FILTER => true,
                self::LABEL => 'Manufacturer', // _('Manufacturer')
                self::NULLABLE => true,
                self::LENGTH => 255,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            // TODO should be a keyfield or record
            self::FLD_ACCOUNTABLE => [
                self::TYPE => self::TYPE_STRING,
                self::QUERY_FILTER => true,
                self::LABEL => 'Accountable', // _('Accountable')
                self::NULLABLE => true,
                self::LENGTH => 40,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            self::FLD_GTIN => [
                self::TYPE => self::TYPE_STRING,
                self::QUERY_FILTER => true,
                self::LABEL => 'GTIN', // _('GTIN')
                self::NULLABLE => true,
                self::LENGTH => 64,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            self::FLD_LIFESPAN_START => [
                self::TYPE => self::TYPE_DATETIME,
                self::LABEL => 'Lifespan start', // _('Lifespan start')
                self::NULLABLE => true,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            self::FLD_LIFESPAN_END => [
                self::TYPE => self::TYPE_DATETIME,
                self::LABEL => 'Lifespan end', // _('Lifespan end')
                self::NULLABLE => true,
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                ]
            ],
            self::FLD_IS_ACTIVE => [
                self::TYPE => self::TYPE_BOOLEAN,
                self::LABEL => 'Is active', // _('Is active')
                self::VALIDATORS => [
                    Zend_Filter_Input::ALLOW_EMPTY => true,
                    Zend_Filter_Input::DEFAULT_VALUE => true
                ],
                self::DEFAULT_VAL => true,
            ],
            self::FLD_SUBPRODUCTS => [
                self::LABEL => 'Subproducts', // _('Subproducts')
                self::TYPE => self::TYPE_RECORDS,
                self::CONFIG => [
                    self::APP_NAME              => Sales_Config::APP_NAME,
                    self::MODEL_NAME            => Sales_Model_SubProductMapping::MODEL_NAME_PART,
                    self::REF_ID_FIELD          => Sales_Model_SubProductMapping::FLD_PARENT_ID,
                    self::DEPENDENT_RECORDS     => true,
                ],
                self::RECURSIVE_RESOLVING => true,
            ],
            self::FLD_SHORTCUT => [
                self::LABEL => 'Shortcut', // _('Shortcut')
                self::TYPE => self::TYPE_STRING,
                self::LENGTH => 20,
                self::NULLABLE => true,
            ],
            self::FLD_IS_SALESPRODUCT => [
                self::LABEL => 'Is Sales Product', // _('Is Sales Product')
                self::TYPE => self::TYPE_BOOLEAN,
                self::DEFAULT_VAL => false,
            ],
            self::FLD_UNFOLD_TYPE => [
                self::LABEL => 'Unfold Type', // _('Category')
                self::TYPE => self::TYPE_KEY_FIELD,
                self::NULLABLE => true,
                self::NAME => Sales_Config::PRODUCT_UNFOLDTYPE,
            ],
            self::FLD_COSTCENTER => [
                self::LABEL => 'Costcenter', // _('Costcenter')
                self::TYPE => self::TYPE_RECORD,
                self::CONFIG => [
                    self::APP_NAME              => Sales_Config::APP_NAME,
                    self::MODEL_NAME            => Sales_Model_CostCenter::MODEL_NAME_PART,
                ],
                self::NULLABLE => true,
            ],
            self::FLD_SALESTAX => [
                self::LABEL => 'Salestax or VAT anybody?', // _('Salestax')
                self::TYPE => self::TYPE_FLOAT,
                self::NULLABLE => true,
            ],
            self::FLD_UNIT => [
                self::LABEL => 'Unit', // _('Unit')
                self::TYPE => self::TYPE_KEY_FIELD,
                self::NULLABLE => true,
                self::NAME => Sales_Config::PRODUCT_UNFOLDTYPE,
            ],
            self::FLD_DEFAULT_GROUPING => [
                self::LABEL => 'Default Grouping', // _('Default Grouping')
                self::TYPE => self::TYPE_STRING,
                self::LENGTH => 255,
                self::NULLABLE => true,
            ],
            self::FLD_DEFAULT_SORT => [
                self::LABEL => 'Default Sort', // _('Default Sort')
                self::TYPE => self::TYPE_STRING,
                self::LENGTH => 255,
                self::NULLABLE => true,
            ]
        ]
    ];

    /**
     * holds the configuration object (must be declared in the concrete class)
     *
     * @var Tinebase_ModelConfiguration
     */
    protected static $_configurationObject = NULL;
}
