<?php
/**
 * SeoUrlFixture
 */
class SeoUrlFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'url' => ['type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_bin', 'charset' => 'utf8'],
        'priority' => ['type' => 'float', 'null' => false, 'default' => null, 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'url' => ['column' => 'url', 'unique' => 0],
            'priority' => ['column' => 'priority', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'MyISAM'],
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 1,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 2,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 2,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 3,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 3,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 4,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 4,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 5,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 5,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 6,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 6,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 7,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 7,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 8,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 8,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 9,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 9,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
        [
            'id' => 10,
            'url' => 'Lorem ipsum dolor sit amet',
            'priority' => 10,
            'created' => '2013-04-19 11:43:35',
            'modified' => '2013-04-19 11:43:35',
        ],
    ];
}
