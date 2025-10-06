<?php

class SeoHoneypotVisit extends SeoAppModel
{
    public $name = 'SeoHoneypotVisit';
    public $displayField = 'ip';
    public $validate = [
        'ip' => [
            'numeric' => [
                'rule' => ['isIp'],
                'message' => 'Specify valid IP',
            ],
        ],
    ];

    /**
     * Fields to IP
     */
    public $fieldsToLong = [
        'ip',
    ];

    /**
     * HoneyPot visit triggered, log the visit in the database.
     *
     * @param string $ip
     * @return bool success
     */
    public function add(?string $ip = null): bool
    {
        if (!$ip) {
            $ip = $this->getIpFromServer();
        }

        $this->clear();

        $this->create();

        return $this->save([
            $this->alias => [
                'ip' => $ip,
            ],
        ]) !== false;
    }

    /**
     * Decide if the trap should be triggered
     *
     * @param string|null $ip to check (default current IP)
     * @return bool
     */
    public function isTriggered(?string $ip = null): bool
    {
        if (!$ip) {
            $ip = $this->getIpFromServer();
        }
        $ip_query = is_numeric($ip) ? $ip : ip2long($ip);

        // Clear the database of old trigger count
        $this->clear();

        // Find the count of triggers within the (not allowed) time frame
        $count = $this->find('count', [
            'conditions' => [
                "{$this->alias}.ip" => $ip_query,
            ],
        ]);

        return SeoUtil::getConfig('triggerCount') <= $count;
    }

    /**
     * Clear the list of old visits baesd on the current time.
     *
     * @return bool success
     */
    public function clear(): bool
    {
        $cutoff = time() - SeoUtil::getConfig('timeBetweenTriggers');

        return $this->deleteAll([
            "{$this->alias}.created <=" => date('Y-m-d g:i:s', $cutoff),
        ]);
    }
}
