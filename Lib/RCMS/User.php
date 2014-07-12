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
    private $_config;

    /**
     * @var object
     */
    private $_db;

    /**
     * @var bool
     */
    private $_logged = false;

    /**
     * @var array
     */
    private $_user = array();

	public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->_config = $registry->getObject("Config");
        $this->_db = $registry->getObject("Database");

		$token = Rev1lZ_Cookies::get("auth_token");

        if ($token === false) {
            $this->_logged = false;
        } else {
            $query = $this->_db
                ->select(array(
                    "`user`"
                ))
                ->from("`" . DBPREFIX . "sessions`")
                ->where("`token`", "=", $this->_db->string($token))
                ->result_array();

            if ($query === false)
                throw new Exception("User error: " . $this->_db->getError());
            elseif (isset($query[0][0]["user"])) {
                $user = $query[0][0]["user"];

                // TODO: Get user info
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
        // TODO: Auth
        return false;
    }

    /**
     * @return bool
     *
     * Check logged user
     */
    public function isLogged() {
		return $this->_logged;
	}

    /**
     * @param $key
     * @return mixed
     *
     * Get user info
     */
    public function get($key) {
        return isset($this->_user[$key]) ? $this->_user[$key] : false;
    }

    /**
     * @param $password
     * @return string
     *
     * Hash User password
     */
    public function passwordHash($password) {
        return hash("sha256", md5($password) . $this->_config->get(0, "User", "PasswordSalt"));
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