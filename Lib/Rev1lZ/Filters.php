<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * Rev1lZ Filters static class
 */

class Rev1lZ_Filters {
    /**
     * @param $str
     * @return string
     *
     * Filter HTML Tags
     */
    public static function filterHtmlTags ($str) {
        return htmlspecialchars($str);
    }

    /**
     * @param $email
     * @return bool
     *
     * Check valid email
     */
    public static function isValidEmail($email) {
        return (boolean) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param $nick
     * @return bool
     *
     * Check valid nickname
     */
    public static function isValidNick($nick) {
        return (boolean) preg_match("/[^a-z0-9._-]/i", $nick);
    }
}