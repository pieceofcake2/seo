<?php

class SeoUrlFixture extends CakeTestFixture
{
    public $name = 'SeoUrl';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'url' => ['type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8'],
        'priority' => ['type' => 'float', 'null' => false, 'default' => null, 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'url' => ['column' => 'url', 'unique' => 1], 'priority' => ['column' => 'priority', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'url' => '/some_other_url',
            'priority' => .5,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
        [
            'id' => 2,
            'url' => '/some_other',
            'priority' => .5,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
        [
            'id' => 3,
            'url' => '/some',
            'priority' => .5,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
        [
            'id' => 4,
            'url' => '/',
            'priority' => 1,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
        [
            'id' => 5,
            'url' => '/content/Hearing-loss/Treatments',
            'priority' => 1,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
        [
            'id' => 6,
            'url' => '/content/articles/Hearing-loss/Protection/30207-Attention-couch-potatoes-time',
            'priority' => 1,
            'created' => '2011-10-10 16:42:47',
            'modified' => '2011-10-10 16:42:47',
        ],
    ];
}
