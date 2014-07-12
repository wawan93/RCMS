<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * Rev1lZ HTTP static class
 */

class Rev1lZ_HTTP {
    /**
     * @var string
     */
    private static $ip = null;

    /**
     * @return string
     *
     * Get Client IP Address
     */
    public static function getIp() {
        if (self::$ip === null) {
            if (!empty($_SERVER["HTTP_CLIENT_IP"]))
                self::$ip = $_SERVER["HTTP_CLIENT_IP"];
            elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
                self::$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            else
                self::$ip = $_SERVER["REMOTE_ADDR"];
        }

        return self::$ip;
    }
}