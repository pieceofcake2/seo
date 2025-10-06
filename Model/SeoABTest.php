<?php

App::uses('SeoAppModel', 'Seo.Model');

/**
 * SeoABTest Model
 *
 * @property SeoUri $SeoUri
 */
class SeoABTest extends SeoAppModel
{
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'slug';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [
        'seo_uri_id' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'Must be associated to an seo_uri',
            ],
        ],
        'priority' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'Priority must be a number',
            ],
        ],
        'redmine' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'Priority must be a number',
                'allowEmpty' => true,
            ],
        ],
        'roll' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Must have a roll must not be empty.',
            ],
            'numberOrCallback' => [
                'rule' => ['numberOrCallback'],
                'message' => 'The roll must either be a number between 1 and 100, or a callback function (Model::function syntax)',
            ],
        ],
        'testable' => [
            'callback' => [
                'rule' => ['testableValidation'],
                'message' => 'testable must either be blank (always true), or a callback function (Model::function syntax)',
                'allowEmpty' => true,
            ],
        ],
        'slug' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Must have a slug (custom Variable for GA)',
            ],
            'noquotes' => [
                'rule' => ['noquotes'],
                'message' => 'The slug cannot contain \' marks.',
            ],
            'isUnique' => [
                'rule' => ['isUnique'],
                'message' => 'This slug already exists.',
            ],
        ],
        'description' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Must have a description of the test.',
            ],
        ],
        'start_date' => [
            'date' => [
                'rule' => ['date'],
                'message' => 'Enter a start valid date.',
                'allowEmpty' => true,
            ],
        ],
        'end_date' => [
            'date' => [
                'rule' => ['date'],
                'message' => 'Enter a end valid date.',
                'allowEmpty' => true,
            ],
        ],
    ];

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        'SeoUri' => [
            'className' => 'Seo.SeoUri',
            'foreignKey' => 'seo_uri_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public $searchFields = [
        'SeoUri.uri', 'SeoABTest.description', 'SeoABTest.slug', 'SeoABTest.id',
    ];

    public $slots = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
    ];

    /**
     * Validate there are no ' marks in the slug
     *
     * @return bool success
     */
    public function noquotes(): bool
    {
        if (isset($this->data[$this->alias]['slug']) && strpos($this->data[$this->alias]['slug'], "'") !== false) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function testableValidation(): bool
    {
        if (isset($this->data[$this->alias]['testable'])) {
            $testable = $this->data[$this->alias]['testable'];
            if (strpos($testable, '::')) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Validate the roll is a number between 1 and 100, or is a callback to a Model::function syntax
     *
     * @return bool success
     */
    public function numberOrCallback(): bool
    {
        if (isset($this->data[$this->alias]['roll'])) {
            $roll = $this->data[$this->alias]['roll'];
            if (is_int($roll) || preg_match('/^\d+$/', $roll)) {
                if ($roll > 0 && $roll <= 100) {
                    return true;
                }
            } elseif (strpos($roll, '::')) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Check if SEO already exists, if so, unset it and set the ID then save.
     *
     * @return bool
     */
    public function beforeSave($options = []): bool
    {
        $this->createOrSetUri();

        return true;
    }

    /**
     * Rolls the test roll.
     *
     * @param mixed string roll, int roll, or array test
     * @return bool success
     */
    public function roll($roll = null): bool
    {
        if (isset($roll[$this->alias]['roll'])) {
            $roll = $roll[$this->alias]['roll'];
        }
        if ($roll) {
            if (strpos($roll, '::')) {
                [$model,$method] = explode('::', $roll);

                return ClassRegistry::init($model)->$method(env('REQUEST_URI'));
            } else {
                return $roll >= rand(1, 100);
            }
        }

        return false;
    }

    /**
     * Take in a test and decide if it's testable.
     *
     * @param mixed string testable or array of test
     * @return bool isTestable
     */
    public function isTestable($testable = null): bool
    {
        if (isset($testable[$this->alias]['testable'])) {
            $testable = $testable[$this->alias]['testable'];
        }
        if (is_string($testable) && $testable) {
            if (strpos($testable, '::')) {
                [$model,$method] = explode('::', $testable);

                return ClassRegistry::init($model)->$method(env('REQUEST_URI'));
            }
        }

        return true;
    }

    /**
     * Find a test and roll to use it.
     *
     * @param string|null request (default to env('REQUEST_URI') if left null)
     * @param bool|null debug, will return tests even if they're not active if true
     * @return bool test if we find a test and rolled successful, or false
     */
    public function findTestableWithRoll(?string $request = null, ?bool $debug = false): bool
    {
        $test = $this->findTestByUri($request, $debug);
        if ($test && $this->isTestable($test) && $this->roll($test)) {
            return true;
        }

        return false;
    }

    /**
     * Decide if we have a test for this request
     *
     * @param string|null request (optional will default to REQUEST_URI
     * @param bool|null debug (default false), if true, will also return non active tests based on the request
     * @return mixed boolean false if no test, otherwise the ABTest will be returned
     */
    public function findTestByUri(?string $request = null, ?bool $debug = false)
    {
        if ($request === null) {
            $request = env('REQUEST_URI');
        }

        $fields = [
            "{$this->SeoUri->alias}.uri",
            "{$this->alias}.slug",
            "{$this->alias}.id",
            "{$this->alias}.roll",
            "{$this->alias}.priority",
            "{$this->alias}.testable",
        ];
        $conditions = [
            'AND' => [
                [
                    'OR' => [
                        "{$this->alias}.start_date IS NULL",
                        "{$this->alias}.start_date <=" => date('Y-m-d'),
                    ],
                ],
                [
                    'OR' => [
                        "{$this->alias}.end_date IS NULL",
                        "{$this->alias}.end_date >=" => date('Y-m-d'),
                    ],
                ],
            ],
        ];
        if (!$debug) {
            $conditions["{$this->alias}.is_active"] = true;
        }

        // Test one of one.
        if (
            $test = $this->find('first', [
            'conditions' => array_merge([
                "{$this->SeoUri->alias}.uri" => $request,
                "{$this->SeoUri->alias}.is_approved" => true,
            ], $conditions),
            'contain' => ["{$this->SeoUri->alias}.uri"],
            'fields' => $fields,
            'order' => "{$this->alias}.priority ASC",
            ])
        ) {
            return $test;
        }

        //Check Many to Many and Many to One
        $cacheEngine = SeoUtil::getConfig('cacheEngine');
        if (!empty($cacheEngine)) {
            $cacheKey = 'seo_ab_tests';
            $tests = Cache::read($cacheKey, $cacheEngine);
        }
        if (!isset($tests) || empty($tests)) {
            $tests = $this->find('all', [
                'conditions' => $conditions,
                'contain' => ["{$this->SeoUri->alias}.uri"],
                'fields' => $fields,
                'order' => "{$this->alias}.priority ASC",
            ]);
            if (!empty($tests) && !empty($cacheEngine)) {
                Cache::write($cacheKey, $tests, $cacheEngine);
            }
        }
        foreach ($tests as $test) {
            if (SeoUtil::requestMatch($request, $test[$this->SeoUri->alias]['uri'])) {
                return $test;
            }
        }

        return false;
    }
}
