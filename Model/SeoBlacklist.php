<?php

class SeoBlacklist extends SeoAppModel
{
    public $name = 'SeoBlacklist';
    public $displayField = 'note';
    public $validate = [
        'ip_range_start' => [
            'numeric' => [
                'rule' => ['isIp'],
                'message' => 'Please specify a valid IP start range',
            ],
        ],
        'ip_range_end' => [
            'numeric' => [
                'rule' => ['isIp'],
                'message' => 'Please specify a valid IP end range',
            ],
        ],
    ];

    /**
     * Fields to IP
     */
    public $fieldsToLong = [
        'ip_range_start',
        'ip_range_end',
    ];

    public $searchFields = ['SeoBlacklist.note'];

    /**
     * Add the IP to the banned list.
     *
     * @param string|null $ip ip to ban
     * @param string $note note to add to this ban
     * @param mixed|null $is_active
     * @return bool success of save
     */
    public function addToBanned($ip = null, $note = 'AutoBanned', $is_active = null)
    {
        if (!$ip) {
            $ip = $this->getIpFromServer();
        }

        if ($is_active === null) {
            $is_active = SeoUtil::getConfig('aggressive');
        }

        return $this->save([
            $this->alias => [
                'ip_range_start' => $ip,
                'ip_range_end' => $ip,
                'note' => $note,
                'is_active' => $is_active,
            ],
        ]) !== false;
    }

    /**
     * Return true depending on the incomming IP
     *
     * @param string|null $ip to check if banned
     * @return bool true or false
     */
    public function isBanned(?string $ip = null)
    {
        if (!$ip) {
            $ip = $this->getIpFromServer();
        }

        $ip_query = is_numeric($ip) ? $ip : ip2long($ip);

        // Check if exists in blacklist
        return $this->hasAny([
            "{$this->alias}.ip_range_start <=" => $ip_query,
            "{$this->alias}.ip_range_end >=" => $ip_query,
            "{$this->alias}.is_active" => true,
        ]);
    }
}
