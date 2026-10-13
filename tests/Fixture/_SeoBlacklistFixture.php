<?php
/**
 * SeoBlacklistFixture
 */
class SeoBlacklistFixture extends CakeTestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
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

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 2,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 3,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 4,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 5,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 6,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 7,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 8,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 9,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
        [
            'id' => 10,
            'ip_range_start' => '',
            'ip_range_end' => '',
            'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2013-04-19 11:42:05',
            'modified' => '2013-04-19 11:42:05',
            'is_active' => 1,
        ],
    ];
}
