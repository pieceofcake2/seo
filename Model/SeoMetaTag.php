<?php

class SeoMetaTag extends SeoAppModel
{
    public $name = 'SeoMetaTag';
    public $displayField = 'name';
    public $validate = [
        'seo_uri_id' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'must be numeric',
            ],
        ],
        'name' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Name must be present.',
            ],
        ],
        'content' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Content must be present.',
            ],
        ],
        'is_http_equiv' => [
            'boolean' => [
                'rule' => ['boolean'],
                'message' => 'Must be true or false',
            ],
        ],
    ];
    public $belongsTo = [
        'SeoUri' => [
            'className' => 'Seo.SeoUri',
            'foreignKey' => 'seo_uri_id',
        ],
    ];

    /**
     * Filter fields
     */
    public $searchFields = [
        'SeoMetaTag.name','SeoMetaTag.content','SeoMetaTag.id','SeoUri.uri',
    ];

    /**
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = [])
    {
        $this->createOrSetUri();

        return true;
    }

    /**
     * Find all the tags by a specific reuqest,
     * This takes in a request URI and finds all matching meta_tags for this URI
     *
     * @param string|null $request incoming request URI
     * @return array of results
     */
    public function findAllTagsByUri(?string $request = null)
    {
        $retval = $this->find('all', [
            'conditions' => [
                "{$this->SeoUri->alias}.uri" => $request,
                "{$this->SeoUri->alias}.is_approved" => true,
            ],
            'contain' => ["{$this->SeoUri->alias}.uri"],
        ]);

        if (!empty($retval)) {
            return $retval;
        }

        $uri_ids = $this->SeoUri->findRegexUri($request);

        if (empty($uri_ids)) {
            return [];
        }

        $retval = $this->find('all', [
            'conditions' => [
                "{$this->alias}.seo_uri_id" => $uri_ids,
            ],
            'contain' => [],
        ]);

        return $retval;
    }
}
