<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * Rev1lZ Cookies static class
 */

class Rev1lZ_Cookies {
    /**
     * @param mided $name
     * @param mixed $value
     * @param int $time
     * @return $this
     *
     * Set cookie
     */
    public static function set($name, $value, $time) {
        setcookie($name, $value, time() + $time, '/');
    }

    /**
     * @param $name
     * @return midex
     *
     * Get cookie
     */
    public static function get($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    /**
     * @param mided $name
     * @return $this
     *
     * Remove cookie
     */
    public static function remove($name) {
        setcookie($name, "", 0);
    }
}
