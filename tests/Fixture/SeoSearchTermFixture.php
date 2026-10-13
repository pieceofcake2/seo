<?php

class SeoSearchTermFixture extends CakeTestFixture
{
    public $name = 'SeoSearchTerm';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'term' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'The term found by Google', 'charset' => 'utf8'],
        'uri' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'The URL this term points to', 'charset' => 'utf8'],
        'count' => ['type' => 'integer', 'null' => false, 'default' => null, 'comment' => 'how many times this term has been searched for'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'term' => 'Lorem ipsum',
            'uri' => '/some_url',
            'count' => 1,
            'created' => '2011-11-18 23:27:41',
        ],
    ];
}
