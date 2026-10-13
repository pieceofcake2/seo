<?php

class SeoCanonicalFixture extends CakeTestFixture
{
    public $name = 'SeoCanonical';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'canonical' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 16,
            'canonical' => '/new_canonical_link',
            'is_active' => 1,
            'created' => '2011-07-27 11:26:10',
            'modified' => '2011-07-27 11:26:10',
        ],
    ];
}
