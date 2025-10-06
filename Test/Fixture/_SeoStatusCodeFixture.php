<?php
/**
 * SeoStatusCodeFixture
 */
class SeoStatusCodeFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'status_code' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '100', 'length' => 4],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 1,
            'status_code' => 1,
            'priority' => 1,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 2,
            'seo_uri_id' => 2,
            'status_code' => 2,
            'priority' => 2,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 3,
            'seo_uri_id' => 3,
            'status_code' => 3,
            'priority' => 3,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 4,
            'seo_uri_id' => 4,
            'status_code' => 4,
            'priority' => 4,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 5,
            'seo_uri_id' => 5,
            'status_code' => 5,
            'priority' => 5,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 6,
            'seo_uri_id' => 6,
            'status_code' => 6,
            'priority' => 6,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 7,
            'seo_uri_id' => 7,
            'status_code' => 7,
            'priority' => 7,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 8,
            'seo_uri_id' => 8,
            'status_code' => 8,
            'priority' => 8,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 9,
            'seo_uri_id' => 9,
            'status_code' => 9,
            'priority' => 9,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
        [
            'id' => 10,
            'seo_uri_id' => 10,
            'status_code' => 10,
            'priority' => 10,
            'is_active' => 1,
            'created' => '2013-04-19 11:43:13',
            'modified' => '2013-04-19 11:43:13',
        ],
    ];
}
