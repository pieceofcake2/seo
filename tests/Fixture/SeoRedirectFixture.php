<?php

class SeoRedirectFixture extends CakeTestFixture
{
    public $name = 'SeoRedirect';

    public $fields = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'redirect' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '100'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'callback' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'is_nocache' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $records = [
        [
            'id' => 1,
            'seo_uri_id' => 1,
            'redirect' => '/',
            'callback' => '',
            'priority' => 10,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 2,
            'seo_uri_id' => 2,
            'redirect' => '/new',
            'callback' => '',
            'priority' => 5,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 3,
            'seo_uri_id' => 3,
            'redirect' => '/',
            'callback' => '',
            'priority' => 1,
            'is_active' => 0,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 4,
            'seo_uri_id' => 4,
            'redirect' => '/priority',
            'callback' => '',
            'priority' => 1,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 5,
            'seo_uri_id' => 5,
            'redirect' => '$1',
            'callback' => '',
            'priority' => 10,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 6,
            'seo_uri_id' => 6,
            'redirect' => '/',
            'callback' => '',
            'priority' => 1,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 7,
            'seo_uri_id' => 7,
            'redirect' => '/questions/$1',
            'callback' => '',
            'priority' => 1,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
        [
            'id' => 8,
            'seo_uri_id' => 8,
            'redirect' => '/{callback}',
            'callback' => 'SeoRedirect::callbackTest',
            'priority' => 1,
            'is_active' => 1,
            'created' => '2010-10-05 18:08:19',
            'is_nocache' => 0,
        ],
    ];
}
