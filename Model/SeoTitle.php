<?php
/**
 * CakePHP Seo Plugin
 *
 * @link https://github.com/webtechnick/CakePHP-Seo-Plugin
 *
 * SeoTitle Model
 * Automatically set the Title based on the URI
 */
class SeoTitle extends SeoAppModel
{
    /**
     * Model Name/Alias
     *
     * @var string
     */
    public $name = 'SeoTitle';

    /**
     * Display Field
     *
     * @var string
     */
    public $displayField = 'title';

    /**
     * Validation Rules
     *
     * @var array
     */
    public $validate = [
        'seo_uri_id' => [
            'unique' => [
                'rule' => ['isUnique'],
                'message' => 'Only one title tag per URI allowed',
            ],
        ],
        'title' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Title must be present',
            ],
        ],
    ];

    /**
     * Belongs To Association
     *
     * @var array
     */
    public $belongsTo = [
        'SeoUri' => [
            'className' => 'Seo.SeoUri',
            'foreignKey' => 'seo_uri_id',
        ],
    ];

    /**
     * Filter fields
     *
     * @var array
     */
    public $searchFields = [
        'SeoTitle.id','SeoTitle.title','SeoTitle.id','SeoUri.uri',
    ];

    /**
     * Assign or create the url.
     *
     * @return bool
     */
    public function beforeSave($options = [])
    {
        $this->createOrSetUri();

        return parent::beforeSave($options);
    }

    /**
     * Find the first title tag that matches this URI
     *
     * @param string incoming reuqest uri
     * @return the first title tag to match
     */
    public function findTitleByUri($request = null)
    {
        return $this->find('first', [
            'conditions' => [
                "{$this->SeoUri->alias}.uri" => $request,
                "{$this->SeoUri->alias}.is_approved" => true,
            ],
            'contain' => ["{$this->SeoUri->alias}.uri"],
            'fields' => ["{$this->alias}.title"],
        ]);
    }
}
