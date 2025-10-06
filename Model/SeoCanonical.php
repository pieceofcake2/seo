<?php

class SeoCanonical extends SeoAppModel
{
    public $name = 'SeoCanonical';
    public $displayField = 'canonical';
    public $validate = [
        'seo_uri_id' => [
            'numeric' => [
                'rule' => ['numeric'],
                'message' => 'Uri Must be present',
            ],
        ],
        'canonical' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'A canonical link must be entered',
            ],
        ],
        'is_active' => [
            'boolean' => [
                'rule' => ['boolean'],
                'message' => 'Your custom message here',
            ],
        ],
    ];
    //The Associations below have been created with all possible keys, those that are not needed can be removed

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
        'SeoCanonical.id','SeoCanonical.canonical','SeoUri.uri',
    ];

    /**
     * Assign or create the url.
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = []): bool
    {
        $this->createOrSetUri();

        return true;
    }

    /**
     * Find the first canonical link that matches this requesting URI
     *
     * @param string|null incoming reuqest uri
     * @return mixed the first canonical link to match
     */
    public function findByUri(?string $request = null)
    {
        return $this->field('canonical', [
            "{$this->SeoUri->alias}.uri" => $request,
            "{$this->SeoUri->alias}.is_approved" => true,
            "{$this->alias}.is_active" => true,
        ]);
    }
}
