<?php
/**
 * Helper class to preform some basic tasks.
 *
 * @author Nick Baker
 * @since 2.0
 * @license MIT
 * @property SeoBlacklist $SeoBlacklist
 */
class SeoUtil extends CakeObject
{
    /**
     * Seo configurations stored in
     * app/config/seo.php
     *
     * @var array
     */
    public static $configs = [];

    /**
     * Return version number
     *
     * @return string version number
     * @access public
     */
    public static function version()
    {
        return '6.1.0';
    }

    /**
     * Return description
     *
     * @return string description
     * @access public
     */
    public static function description()
    {
        return 'CakePHP Search Engine Optimization Plugin';
    }

    /**
     * Return author
     *
     * @return string author
     * @access public
     */
    public static function author()
    {
        return 'Nick Baker, Alan Blount';
    }

    /**
     * Load the SeoAppError class
     *
     * @return bool
     */
    public static function loadSeoError()
    {
        if (class_exists(SeoAppError::class)) {
            return true;
        }

        App::uses('SeoAppError', 'Seo.Lib/Error');
        if (class_exists(SeoAppError::class)) {
            return true;
        }

        return false;
    }

    /**
     * Utility method to call Seo.SeoBlacklist::isBanned($ip);
     *
     * @param string|null $ip
     * @return bool
     */
    public function isBanned(?string $ip = null)
    {
        if (!isset($this->SeoBlacklist)) {
            $this->SeoBlacklist = ClassRegistry::init('Seo.SeoBlacklist');
        }

        return $this->SeoBlacklist->isBanned($ip);
    }

    /**
     * Testing getting a configuration option.
     *
     * @param string $key key to search for
     * @return mixed result of configuration key.
     */
    public static function getConfig(string $key)
    {
        if (isset(self::$configs[$key])) {
            return self::$configs[$key];
        }

        // try configure setting
        if (self::$configs[$key] = Configure::read("Seo.$key")) {
            return self::$configs[$key];
        }

        // try load configuration file and try again.
        Configure::load('seo');
        self::$configs = Configure::read('Seo');
        if (self::$configs[$key] = Configure::read("Seo.$key")) {
            return self::$configs[$key];
        }

        return null;
    }

    /**
     * Return if the incoming URI is a regular expression
     *
     * @param string $uri
     * @return bool if is regular expression (as two # marks)
     */
    public static function isRegEx(string $uri): bool
    {
        return preg_match('/^#(.*)#(.*)/', $uri);
    }

    /**
     * Given a request, see if the uri matches.
     *
     * @param string request
     * @param string|null uri
     * @return bool if request matches the URI given
     */
    public static function requestMatch(string $request, ?string $uri = null): bool
    {
        if ($uri) {
            if (self::isRegEx($uri) && preg_match($uri, $request)) {
                //Many To Many --using regular expression
                return true;
            } elseif (strpos($uri, '*') !== false) {
                //Many to One -- Check for * wildcard in uri, if present only match up to the * in the request.
                $uri = str_replace('*', '', $uri);
                if (strpos($request, $uri) === 0) {
                    return true;
                }
            } elseif (strtolower($uri) == strtolower($request)) {
                //One to One
                return true;
            }
        }

        return false;
    }
}
