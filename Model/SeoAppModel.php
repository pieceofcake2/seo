<?php
/**
 * CakePHP Seo Plugin
 *
 * @link https://github.com/webtechnick/CakePHP-Seo-Plugin
 *
 * App Model
 */
App::uses('SeoUtil', 'Seo.Lib');
class SeoAppModel extends AppModel
{
    /**
     * Always use Containable
     *
     * var array
     */
    public $actsAs = ['Containable'];

    /**
     * Always set recursive = 0
     * (we'd rather use containable for more control)
     *
     * var int
     */
    public $recursive = 0;

    /**
     * Overwritable IP fields for database saving
     *
     * @var array
     */
    public $fieldsToLong = [];

    /**
     * Filter fields
     *
     * @var array
     */
    public $searchFields = [];

    /**
     * standard beforeSave() callback
     * Save string IPs as longs
     *
     * @return true
     */
    public function beforeSave($options = [])
    {
        foreach ($this->fieldsToLong as $field) {
            if (isset($this->data[$this->alias][$field]) && !is_numeric($this->data[$this->alias][$field])) {
                $this->data[$this->alias][$field] = ip2long($this->data[$this->alias][$field]);
            }
        }

        return parent::beforeSave($options);
    }

    /**
     * Show the IPs back out.
     *
     * @param mixed $results
     * @param bool $primary
     * @return mixed formatted results
     */
    public function afterFind($results, $primary = false)
    {
        if (!is_array($results)) {
            return $results;
        }

        foreach ($results as $key => $val) {
            foreach ($this->fieldsToLong as $field) {
                if (isset($val[$this->alias][$field]) && is_numeric($val[$this->alias][$field])) {
                    $results[$key][$this->alias][$field] = long2ip($val[$this->alias][$field]);
                }
            }
        }

        return $results;
    }

    /**
     * Overwrite find so I can do some nice things with it.
     *
     * @param string $type find type - last : find last record by created date
     * @param array $query array of query
     * @return array|int|null
     */
    public function find($type = 'first', $query = [])
    {
        switch ($type) {
            case 'last':
                $query = array_merge(
                    $query,
                    ['order' => "{$this->alias}.{$this->primaryKey} DESC"],
                );

                return parent::find('first', $query);
            default:
                return parent::find($type, $query);
        }
    }

    /**
     * Custom validation.
     * Using CakePHP IP validation would be nice, but
     * since we're storing ips as longs in our database
     * we need a custom validation.
     *
     * @param array<string> $check field to check
     * @return bool
     */
    public function isIp(array $check = [])
    {
        $ip_to_check = array_shift($check);

        return ip2long($ip_to_check) !== false;
    }

    /**
     * Set or create the model, this is useful to find the URI
     *
     * @param string $model
     * @param string $field
     * @return bool
     */
    public function createOrSetUri($model = 'SeoUri', $field = 'uri')
    {
        $ModelName = Inflector::camelize($model);
        $model_underscore = Inflector::underscore($model);

        if (isset($this->data[$ModelName][$field])) {
            $this->$ModelName->contain();
            $this->$ModelName->recursive = -1;
            //Find the Model, and set the id.
            if ($associated_id = $this->$ModelName->field('id', [$field => $this->data[$ModelName][$field]])) {
                $this->data[$this->alias][$model_underscore . '_id'] = $associated_id;
            } else {
                $save = [];
                $save[$ModelName][$field] = $this->data[$ModelName][$field];
                $this->$ModelName->clear();
                $this->$ModelName->save($save);
                $this->data[$this->alias][$model_underscore . '_id'] = $this->$ModelName->id;
            }
        }

        return true;
    }

    /**
     * Return if the incoming URI is a regular expression
     *
     * @param string $uri
     * @return bool if is regular expression (as two # marks)
     */
    public function isRegEx($uri): bool
    {
        return SeoUtil::isRegEx($uri);
    }

    /**
     * return conditions based on searchable fields and filter
     *
     * @param string|null filter
     * @return array conditions array
     */
    public function generateFilterConditions(?string $filter = null)
    {
        $retval = [];
        if ($filter) {
            foreach ($this->searchFields as $field) {
                $retval['OR']["$field LIKE"] = '%' . $filter . '%';
            }
        }

        return $retval;
    }

    /**
     * Returns the server IP based on values from the $_SERVER, etc
     *
     * @return string $ip of incoming/client IP
     */
    public function getIpFromServer()
    {
        $check_order = [
            'HTTP_CLIENT_IP', // shared client
            'HTTP_X_FORWARDED_FOR', // proxy address (nginx, etc)
            'REMOTE_ADDR', // fail safe
        ];
        foreach ($check_order as $key) {
            $ip = env($key);
            if (!empty($ip)) {
                return $ip;
            }
        }
    }

    /**
     * This is what I want create to do, but without setting defaults.
     *
     * @return array
     */
    public function clear()
    {
        $this->id = false;
        $this->data = [];
        $this->validationErrors = [];

        return $this->data;
    }
}
