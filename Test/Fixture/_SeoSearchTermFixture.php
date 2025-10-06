<?php
/**
 * SeoSearchTermFixture
 */
class SeoSearchTermFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'term' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'The term found by Google', 'charset' => 'utf8'],
        'uri' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'The URL this term points to', 'charset' => 'utf8'],
        'count' => ['type' => 'integer', 'null' => false, 'default' => null, 'comment' => 'how many times this term has been searched for'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
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
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 1,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 2,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 2,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 3,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 3,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 4,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 4,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 5,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 5,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 6,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 6,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 7,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 7,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 8,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 8,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 9,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 9,
            'created' => '2013-04-19 11:43:06',
        ],
        [
            'id' => 10,
            'term' => 'Lorem ipsum dolor sit amet',
            'uri' => 'Lorem ipsum dolor sit amet',
            'count' => 10,
            'created' => '2013-04-19 11:43:06',
        ],
    ];
}
