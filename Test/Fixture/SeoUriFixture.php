<?php

class SeoUriFixture extends CakeTestFixture
{
    public $name = 'SeoUri';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uri' => ['type' => 'string', 'null' => true, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_approved' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'uri' => ['column' => 'uri', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'uri' => '/blah',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 2,
            'uri' => '/blahblah*',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 3,
            'uri' => '/not_active',
            'is_approved' => 0,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 4,
            'uri' => '/blahblahblah*',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 5,
            'uri' => '#(.*)\?from\=sb\-tracked\:(.*)#i',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 6,
            'uri' => '#(.*)#i',
            'is_approved' => 0,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 7,
            'uri' => '#/qas/(.*)#',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 8,
            'uri' => '/uri',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 9,
            'uri' => '/uri_for_meta',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 10,
            'uri' => '/uri_for_meta_equiv',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 11,
            'uri' => '#/uri_for_meta_reg_ex/(.*)#',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 12,
            'uri' => '/uri_for_meta_reg_ex/this_is_direct_match',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 13,
            'uri' => '/uri_for_meta_wild_card/*',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 14,
            'uri' => '/uri with spaces',
            'is_approved' => 0,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 15,
            'uri' => '/status_gone',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
        [
            'id' => 16,
            'uri' => '/canonical',
            'is_approved' => 1,
            'created' => '2011-01-03 10:04:34',
            'modified' => '2011-01-03 10:04:34',
        ],
    ];
}
