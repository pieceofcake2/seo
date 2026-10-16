<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('Router', 'Routing');

class SeoUri extends SeoAppModel
{
    /**
     * Email instance used to send approval notifications
     *
     * @var CakeEmail|null
     */
    public $Email = null;

    /**
     * Nesting level of deferNotifications() calls
     *
     * @var int
     */
    protected $_notificationsDeferred = 0;

    /**
     * Ids of the URIs whose approval notification waits for the end of a save
     *
     * @var array<int|string>
     */
    protected $_pendingNotifications = [];

    /**
     * Model Name/Alias
     *
     * @var string
     */
    public $name = 'SeoUri';

    /**
     * Model Display Field
     *
     * @var string
     */
    public $displayField = 'uri';

    /**
     * Has Many Association
     *
     * @var array
     */
    public $hasMany = [
        'SeoMetaTag' => [
            'className' => 'Seo.SeoMetaTag',
            'foreignKey' => 'seo_uri_id',
            'dependent' => true,
        ],
        'SeoABTest' => [
            'className' => 'Seo.SeoABTest',
            'foreignKey' => 'seo_uri_id',
            'dependent' => true,
        ],
    ];

    /**
     * Has One Association
     *
     * @var array
     */
    public $hasOne = [
        'SeoRedirect' => [
            'className' => 'Seo.SeoRedirect',
            'foreignKey' => 'seo_uri_id',
            'dependent' => true,
        ],
        'SeoTitle' => [
            'className' => 'Seo.SeoTitle',
            'foreignKey' => 'seo_uri_id',
            'dependant' => true,
        ],
        'SeoCanonical' => [
            'className' => 'Seo.SeoCanonical',
            'foreignKey' => 'seo_uri_id',
            'dependant' => true,
        ],
        'SeoStatusCode' => [
            'className' => 'Seo.SeoStatusCode',
            'foreignKey' => 'seo_uri_id',
            'dependant' => true,
        ],
    ];

    /**
     * Validation Rules
     *
     * @var array
     */
    public $validate = [
        'uri' => [
            'unique' => [
                'rule' => ['isUnique'],
                'message' => 'Must be a unique url',
            ],
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Uri Must be present',
            ],
        ],
    ];

    /**
     * Filter fields
     *
     * @var array
     */
    public $searchFields = [
        'SeoUri.id','SeoUri.uri',
    ];

    /**
     * If saving a regular expression, make sure to mark not approved unless
     * is_approved is specifically being sent in.
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = [])
    {
        //url encode the uri, but only once.
        if (!empty($this->data[$this->alias]['uri']) && $this->isRegEx($this->data[$this->alias]['uri'])) {
            if (empty($this->data[$this->alias]['is_approved'])) {
                $this->data[$this->alias]['is_approved'] = false;
            }
        } else {
            $this->data[$this->alias]['is_approved'] = true;
        }

        return parent::beforeSave($options);
    }

    /**
     * Send need approval email if we need it.
     *
     * @param bool $created
     * @param array $options
     * @return void
     */
    public function afterSave($created, $options = [])
    {
        if ($created) {
            //Maybe URI
        }
        if (isset($this->data[$this->alias]['is_approved']) && !$this->data[$this->alias]['is_approved']) {
            //Email IT about needing approval... currently me.
            if ($this->_notificationsDeferred > 0) {
                $this->_pendingNotifications[] = $this->id;
            } else {
                $this->sendNotification();
            }
        }

        parent::afterSave($created, $options);
    }

    /**
     * Save the URI with its associated records (SeoRedirect, SeoMetaTag, ...).
     *
     * The approval notification is sent after the associated records are saved,
     * so that it contains the saved redirect.
     *
     * @param array|null $data
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function saveAssociated($data = null, $options = [])
    {
        $this->deferNotifications();
        try {
            $result = parent::saveAssociated($data, $options);
        } catch (Exception $e) {
            $this->sendDeferredNotifications(false);

            throw $e;
        }
        $this->sendDeferredNotifications($result !== false);

        return $result;
    }

    /**
     * Hold back the approval notifications until sendDeferredNotifications() is called.
     *
     * Use this when saving a URI before its redirect, so that the notification
     * contains the saved redirect. Calls can be nested.
     *
     * @return void
     */
    public function deferNotifications()
    {
        if ($this->_notificationsDeferred === 0) {
            $this->_pendingNotifications = [];
        }
        $this->_notificationsDeferred++;
    }

    /**
     * End a deferNotifications() call and, when it is the outermost one, send the
     * approval notifications held back since then.
     *
     * @param bool $send false to discard the held back notifications (e.g. the save failed)
     * @return void
     */
    public function sendDeferredNotifications($send = true)
    {
        if ($this->_notificationsDeferred > 0) {
            $this->_notificationsDeferred--;
        }
        if ($this->_notificationsDeferred > 0) {
            return;
        }

        $ids = array_unique($this->_pendingNotifications);
        $this->_pendingNotifications = [];
        if ($send) {
            foreach ($ids as $id) {
                $this->sendNotification($id);
            }
        }
    }

    /**
     * Url encode the uri
     *
     * @param int|null $id
     * @return bool success
     */
    public function urlEncode($id = null)
    {
        if ($id) {
            $this->id = $id;
        }

        $uri = $this->field('uri');
        $uri = rawurlencode($uri);
        $uri = str_replace('%2F', '/', $uri);

        return $this->save(['uri' => $uri]) !== false;
    }

    /**
     * Named scope to find for view
     *
     * @param int $id
     * @return mixed result of find.
     */
    public function findForViewById($id)
    {
        return $this->find('first', [
            'conditions' => ['SeoUri.id' => $id],
            'contain' => ['SeoRedirect','SeoTitle','SeoMetaTag','SeoStatusCode'],
        ]);
    }

    /**
     * Find the URI id by uri
     *
     * @param string|null $uri
     * @return mixed id
     */
    public function findIdByUri($uri = null)
    {
        return $this->field('id', ["{$this->alias}.uri" => $uri]);
    }

    /**
     * This is a simple function to return all possible RegEx URIs from the DB
     * (it has to return all of them, since we can't know which it's going to match)
     * So we've wrapped the DB request in a simple cache request,
     *   configured by setting the config key cacheEngine
     *
     * @return array $uris array(id => uri)
     */
    public function findAllRegexUris()
    {
        $cacheEngine = SeoUtil::getConfig('cacheEngine');
        if (!empty($cacheEngine)) {
            $cacheKey = 'seo_findallregexuris';
            $uris = Cache::read($cacheKey, $cacheEngine);
        }
        if (!isset($uris) || empty($uris)) {
            $uris = $this->find('all', [
                'conditions' => [
                    'OR' => [
                        ["{$this->alias}.uri LIKE" => '#%'],
                        ["{$this->alias}.uri LIKE" => '%*'],
                        ],
                    "{$this->alias}.is_approved" => true,
                    ],
                'contain' => [],
                'fields' => ["{$this->alias}.id","{$this->alias}.uri"],
                ]);
            if (!empty($uris) && !empty($cacheEngine)) {
                Cache::write($cacheKey, $uris, $cacheEngine);
            }
        }
        if (!is_array($uris)) {
            return [];
        }

        return $uris;
    }

    /**
     * Checks an input $request against regex urls
     *
     * @param string|null $request
     * @return array $uri_ids array(id)
     */
    public function findRegexUri(?string $request = null)
    {
        $uri_ids = [];
        $uris = $this->findAllRegexUris();
        foreach ($uris as $uri) {
            //Wildcard match
            if (strpos($request, str_replace('*', '', $uri[$this->alias]['uri'])) !== false) {
                $uri_ids[] = $uri[$this->alias]['id'];
            } elseif ($this->isRegex($uri[$this->alias]['uri']) && preg_match($uri[$this->alias]['uri'], $request)) {
                //Regex match
                $uri_ids[] = $uri[$this->alias]['id'];
            }
        }

        return $uri_ids;
    }

    /**
     * Set as approved
     *
     * @param int|null $id id of seo redirect to approve
     * @return bool result of save
     */
    public function setApproved($id = null)
    {
        if ($id) {
            $this->id = $id;
        }

        return $this->saveField('is_approved', true);
    }

    /**
     * Send the notification of a regular expression that needs approval.
     *
     * @param int|null $id
     * @return void
     */
    public function sendNotification($id = null)
    {
        if ($id) {
            $this->id = $id;
        }
        $this->data = $this->find('first', [
            'conditions' => ["{$this->alias}.{$this->primaryKey}" => $this->id],
            'contain' => [$this->SeoRedirect->alias],
        ]);

        if (!empty($this->data)) {
            if (!isset($this->Email)) {
                $this->Email = new CakeEmail();
                if ($email_config = SeoUtil::getConfig('emailConfig')) {
                    $this->Email->config($email_config);
                }
            }

            $this->Email->to(SeoUtil::getConfig('approverEmail'));
            $this->Email->from(SeoUtil::getConfig('replyEmail'));
            $this->Email->subject("301 Redirect: {$this->data[$this->alias]['uri']} to {$this->data[$this->SeoRedirect->alias]['redirect']} needs approval");
            $this->Email->emailFormat('html');

            $message = <<<EOT
                A new regular expression 301 redirect needs to be approved.<br />
                <br />
                URI: {$this->data[$this->alias]['uri']}<br />
                REDIRECT: {$this->data[$this->SeoRedirect->alias]['redirect']}<br />
                PRIORITY: {$this->data[$this->SeoRedirect->alias]['priority']}<br />
                <br />
                Link to approve:<br />
                EOT . $this->approveUrl($this->data[$this->alias]['id']) . '<br /><br />';

            $this->Email->send($message);
        }
    }

    /**
     * Full URL of the admin action that approves the URI (SeoUrisController::admin_approve()).
     *
     * The `parentDomain` setting, when set, is used as the scheme and host,
     * otherwise the full base URL of the application (`App.fullBaseUrl`).
     *
     * @param string|int $id id of the SeoUri
     * @return string
     */
    public function approveUrl($id)
    {
        $url = [
            'admin' => true,
            'plugin' => 'seo',
            'controller' => 'seo_uris',
            'action' => 'approve',
            $id,
        ];

        $parentDomain = SeoUtil::getConfig('parentDomain');
        if (!empty($parentDomain)) {
            return rtrim($parentDomain, '/') . Router::url($url);
        }

        return Router::url($url, true);
    }

    /**
     * Given a request, see if the uri matches.
     *
     * @param string $request
     * @param string|int|null $uri uri or uri_id to look up
     * @return bool if request matches the URI given
     */
    public function requestMatch($request, $uri = null)
    {
        if (is_int($uri)) {
            $uri = $this->field('uri', ['SeoUri.id' => $uri]);
        }

        return SeoUtil::requestMatch($request, $uri);
    }
}
