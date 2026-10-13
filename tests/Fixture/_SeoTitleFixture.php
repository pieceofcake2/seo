<?php
/**
 * SeoTitleFixture
 */
class SeoTitleFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'title' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
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
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 2,
            'seo_uri_id' => 2,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 3,
            'seo_uri_id' => 3,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 4,
            'seo_uri_id' => 4,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 5,
            'seo_uri_id' => 5,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 6,
            'seo_uri_id' => 6,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 7,
            'seo_uri_id' => 7,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 8,
            'seo_uri_id' => 8,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 9,
            'seo_uri_id' => 9,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
        [
            'id' => 10,
            'seo_uri_id' => 10,
            'title' => 'Lorem ipsum dolor sit amet',
            'created' => '2013-04-19 11:41:34',
            'modified' => '2013-04-19 11:41:34',
        ],
    ];
}
