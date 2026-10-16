<?php

class SeoRedirect extends SeoAppModel
{
    public $name = 'SeoRedirect';

    /**
     * Request passed to callbackTest(), for testing.
     *
     * @var mixed
     */
    public $uri_request = null;

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
     * Save the redirect, creating its SeoUri when needed.
     *
     * The approval notification of a new regular expression URI is sent after
     * the redirect is saved, so that it contains the saved redirect.
     *
     * @param array|null $data
     * @param array|bool $validate
     * @param array $fieldList
     * @return mixed
     * @throws Exception
     */
    public function save($data = null, $validate = true, $fieldList = [])
    {
        $this->SeoUri->deferNotifications();
        try {
            $result = parent::save($data, $validate, $fieldList);
        } catch (Exception $e) {
            $this->SeoUri->sendDeferredNotifications(false);

            throw $e;
        }
        $this->SeoUri->sendDeferredNotifications($result !== false);

        return $result;
    }

    /**
     * Save the redirect with its associated records (e.g. a new SeoUri).
     *
     * The approval notification of a new regular expression URI is sent after
     * the redirect is saved, so that it contains the saved redirect.
     *
     * @param array|null $data
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function saveAssociated($data = null, $options = [])
    {
        $this->SeoUri->deferNotifications();
        try {
            $result = parent::saveAssociated($data, $options);
        } catch (Exception $e) {
            $this->SeoUri->sendDeferredNotifications(false);

            throw $e;
        }
        $this->SeoUri->sendDeferredNotifications($result !== false);

        return $result;
    }

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
