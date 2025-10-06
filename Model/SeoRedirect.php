<?php

class SeoRedirect extends SeoAppModel
{
    public $name = 'SeoRedirect';
    public $displayField = 'uri';
    public $validate = [
        'redirect' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Redirect must not be empty',
            ],
        ],
        'priority' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'Priorty must be an integer number',
            ],
        ],
    ];

    public $belongsTo = [
        'Seo.SeoUri',
    ];

    /**
     * Filter fields
     */
    public $searchFields = [
        'SeoRedirect.redirect','SeoRedirect.callback','SeoRedirect.id','SeoUri.uri',
    ];

    /**
     * Check if SEO already exists, if so, unset it and set the ID then save.
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = [])
    {
        $this->createOrSetUri();

        return true;
    }

    /**
     * This is a helper function for testing.
     *
     * @param mixed $request
     * @return string
     */
    public function callbackTest($request)
    {
        $this->uri_request = $request;

        return 'ran_callback';
    }

    /**
     * Named scope to find list of uri -> redirect by order and approved/active
     *
     * @return array list of active and approved uri -> redirects ordered by priority
     */
    public function findRedirectListByPriority(): array
    {
        return $this->find('all', [
            'contain' => [$this->SeoUri->alias => 'uri'],
            'fields' => [
                "{$this->alias}.redirect",
                "{$this->alias}.id",
                "{$this->alias}.callback",
                "{$this->alias}.is_nocache",
            ],
            'order' => ["{$this->alias}.priority" => 'ASC'],
            'conditions' => [
                "{$this->alias}.is_active" => true,
                "{$this->SeoUri->alias}.is_approved" => true,
            ],
        ]);
    }
}
