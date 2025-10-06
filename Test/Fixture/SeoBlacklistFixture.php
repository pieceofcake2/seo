<?php

class SeoBlacklistFixture extends CakeTestFixture
{
    public $name = 'SeoBlacklist';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ip_range_start' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index'],
        'ip_range_end' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index'],
        'note' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'ip_range_start' => ['column' => 'ip_range_start', 'unique' => 0], 'ip_range_end' => ['column' => 'ip_range_end', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'ip_range_start' => 2147483000,
            'ip_range_end' => 2147483002,
            'note' => 'This is a note',
            'is_active' => 1,
            'created' => '2011-02-02 11:19:31',
            'modified' => '2011-02-02 11:19:31',
        ],
        [
            'id' => 2,
            'ip_range_start' => 2147483100,
            'ip_range_end' => 2147483100,
            'note' => 'This is a note',
            'is_active' => 0, //not active
            'created' => '2011-02-02 11:19:31',
            'modified' => '2011-02-02 11:19:31',
        ],
    ];
}
