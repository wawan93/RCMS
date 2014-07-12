<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS User class
 */

class RCMS_User {
    /**
     * @var object
     */
    private $config;

    /**
     * @var object
     */
    private $db;

    /**
     * @var bool
     */
    private $logged = false;

    /**
     * @var array
     */
    private $user = array();

	public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->config = $registry->getObject("Config");
        $this->db = $registry->getObject("Database");

		$token = Rev1lZ_Cookies::get("auth_token");

        if ($token === false) {
            $this->logged = false;
        } else {
            $query = $this->db
                ->select(array(
                    "`user`"
                ))
                ->from("`" . DBPREFIX . "sessions`")
                ->where("`token`", "=", "'" . $this->db->safe($token) . "'")
                ->result_array();

            if ($query === false)
                throw new Exception("User error: " . $this->db->getError());
            elseif (isset($query[0][0]["user"])) {
                $user = $query[0][0]["user"];
            } else
                Rev1lZ_Cookies::remove("auth_token");
        }
	}

    /**
     * @param $login
     * @param $password
     * @return bool
     *
     * External User Auth
     */
    public function externalAuth($login, $password) {
        return false;
    }

    /**
     * @return bool
     *
     * Check logged user
     */
    public function isLogged() {
		return $this->logged;
	}

    /**
     * @param $key
     * @return mixed
     *
     * Get user info
     */
    public function get($key) {
        return isset($this->user[$key]) ? $this->user[$key] : false;
    }

    /**
     * @param $password
     * @return string
     *
     * Hash User password
     */
    public function passwordHash($password) {
        return hash("sha256", md5($password) . $this->config->get(0, "User", "PasswordSalt"));
    }

    /**
     * @param $userId
     * @param $login
     * @return string
     *
     * Generate user token
     */
    public function genToken($userId, $login) {
        $hash = "";

        for ($i = 1; $i <= 32; $i++) {
            $hash .= mt_rand(0, 9);
        }

		return hash("sha512", $userId . "_" . $login . "_" . $hash);
	}
}