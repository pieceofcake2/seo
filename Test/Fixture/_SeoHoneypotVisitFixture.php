<?php
/**
 * SeoHoneypotVisitFixture
 */
class SeoHoneypotVisitFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ip' => ['type' => 'biginteger', 'null' => false, 'default' => null, 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'ip' => ['column' => 'ip', 'unique' => 0],
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
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 2,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 3,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 4,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 5,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 6,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 7,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 8,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 9,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
        [
            'id' => 10,
            'ip' => '',
            'created' => '2013-04-19 11:42:25',
        ],
    ];
}
