<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * User Auth Model
 */

class App_User_Model_Auth extends RCMS_Model {
	public function __construct() {
        parent::__construct();

        $this->title = $this->_lang->get(1, "Auth", "moduleName");
        $this->viewName = "User/Auth/Page";
        $this->tags = array (
            "register-link" => SITE_PATH
        );
	}
}