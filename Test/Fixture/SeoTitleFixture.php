<?php

class SeoTitleFixture extends CakeTestFixture
{
    public $name = 'SeoTitle';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'title' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 1,
            'title' => 'Title',
            'created' => '2011-01-05 18:15:00',
            'modified' => '2011-01-05 18:15:00',
        ],
    ];
}
