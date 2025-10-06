<?php

class SeoMetaTagFixture extends CakeTestFixture
{
    public $name = 'SeoMetaTag';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'name' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'content' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_http_equiv' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 9,
            'name' => 'keywords',
            'content' => 'content_1',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 2,
            'seo_uri_id' => 9,
            'name' => 'description',
            'content' => 'content_2',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 3,
            'seo_uri_id' => 10,
            'name' => 'content-type',
            'content' => 'text/html',
            'is_http_equiv' => 1,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 4,
            'seo_uri_id' => 11,
            'name' => 'default',
            'content' => 'content_default',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 5,
            'seo_uri_id' => 11,
            'name' => 'description_default',
            'content' => 'content_default_2',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 6,
            'seo_uri_id' => 12,
            'name' => 'direct_match',
            'content' => 'direct_match_content',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
        [
            'id' => 7,
            'seo_uri_id' => 13,
            'name' => 'wild_card_match',
            'content' => 'wild_card_match_content',
            'is_http_equiv' => 0,
            'created' => '2011-01-03 10:04:07',
            'modified' => '2011-01-03 10:04:07',
        ],
    ];
}
