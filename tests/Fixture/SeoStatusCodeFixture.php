<?php

class SeoStatusCodeFixture extends CakeTestFixture
{
    public $name = 'SeoStatusCode';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'status_code' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '100', 'length' => 4],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0]],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 15,
            'status_code' => 410,
            'priority' => 100,
            'is_active' => 1,
            'created' => '2011-07-25 17:06:20',
            'modified' => '2011-07-25 17:06:20',
        ],
    ];
}
