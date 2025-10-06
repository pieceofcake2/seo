<?php
/**
 * SeoMetaTagFixture
 */
class SeoMetaTagFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'name' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'content' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_http_equiv' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
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
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 2,
            'seo_uri_id' => 2,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 3,
            'seo_uri_id' => 3,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 4,
            'seo_uri_id' => 4,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 5,
            'seo_uri_id' => 5,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 6,
            'seo_uri_id' => 6,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 7,
            'seo_uri_id' => 7,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 8,
            'seo_uri_id' => 8,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 9,
            'seo_uri_id' => 9,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
        [
            'id' => 10,
            'seo_uri_id' => 10,
            'name' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet',
            'is_http_equiv' => 1,
            'created' => '2013-04-19 11:42:34',
            'modified' => '2013-04-19 11:42:34',
        ],
    ];
}
