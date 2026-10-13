<?php

class SeoHoneypotVisitFixture extends CakeTestFixture
{
    public $name = 'SeoHoneypotVisit';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ip' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'ip' => 2147483000,
            'created' => '2011-01-01 19:03:42',
        ],
    ];
}
