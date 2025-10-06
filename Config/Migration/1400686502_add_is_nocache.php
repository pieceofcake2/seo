<?php
class AddIsNocache extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     * @access public
     */
    public $description = '';

    /**
     * Actions to be performed
     *
     * @var array $migration
     * @access public
     */
    public $migration = [
        'up' => [
            'create_field' => [
                'seo_redirects' => [
                    'is_nocache' => ['type' => 'boolean', 'null' => true, 'default' => 0],
                ],
            ],
        ],
        'down' => [
            'drop_field' => [
                'seo_redirects' => ['is_nocache'],
            ],
        ],
    ];

    /**
     * Before migration callback
     *
     * @param string $direction, up or down direction of migration process
     * @return bool Should process continue
     * @access public
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback
     *
     * @param string $direction, up or down direction of migration process
     * @return bool Should process continue
     * @access public
     */
    public function after($direction)
    {
        return true;
    }
}
