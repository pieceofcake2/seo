<?php
class SeoSchema extends CakeSchema
{
    /**
     * @param array $event
     * @return true
     */
    public function before($event = [])
    {
        return true;
    }

    /**
     * @param array $event
     * @return void
     */
    public function after($event = [])
    {
    }

    public $seo_a_b_tests = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'],
        'slug' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'roll' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'comment' => 'int based roll or Model::function callback', 'charset' => 'utf8'],
        'testable' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'comment' => 'model callback or empty', 'charset' => 'utf8'],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '999', 'length' => 4, 'key' => 'index', 'comment' => 'lower the priority, the more priority it has over the other tests.'],
        'redmine' => ['type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'redmine ticket id'],
        'description' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'start_date' => ['type' => 'date', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'if null, we ignore it.'],
        'end_date' => ['type' => 'date', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'if null, we ignore it.'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'slug' => ['column' => 'slug', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
            'is_active' => ['column' => 'is_active', 'unique' => 0],
            'priority' => ['column' => 'priority', 'unique' => 0],
            'end_date' => ['column' => 'end_date', 'unique' => 0],
            'start_date' => ['column' => 'start_date', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_blacklists = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ip_range_start' => ['type' => 'biginteger', 'null' => false, 'default' => null, 'key' => 'index'],
        'ip_range_end' => ['type' => 'biginteger', 'null' => false, 'default' => null, 'key' => 'index'],
        'note' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'ip_range_start' => ['column' => 'ip_range_start', 'unique' => 0],
            'ip_range_end' => ['column' => 'ip_range_end', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_canonicals = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'canonical' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
            'is_active' => ['column' => 'is_active', 'unique' => 0],
            'created' => ['column' => 'created', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_honeypot_visits = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ip' => ['type' => 'biginteger', 'null' => false, 'default' => null, 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'ip' => ['column' => 'ip', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_meta_tags = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'name' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'content' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'is_http_equiv' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_redirects = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'redirect' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '100'],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'callback' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'is_nocache' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_search_terms = [
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

    public $seo_status_codes = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'status_code' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3],
        'priority' => ['type' => 'integer', 'null' => false, 'default' => '100', 'length' => 4],
        'is_active' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_titles = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'seo_uri_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'],
        'title' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'seo_uri_id' => ['column' => 'seo_uri_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_uris = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uri' => ['type' => 'string', 'null' => true, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8'],
        'is_approved' => ['type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uri' => ['column' => 'uri', 'unique' => 1],
            'is_approved' => ['column' => 'is_approved', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'],
    ];

    public $seo_urls = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'url' => ['type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_bin', 'charset' => 'utf8'],
        'priority' => ['type' => 'float', 'null' => false, 'default' => null, 'key' => 'index'],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'url' => ['column' => 'url', 'unique' => 1],
            'priority' => ['column' => 'priority', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'MyISAM'],
    ];
}
