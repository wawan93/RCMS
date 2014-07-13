<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * User Frontend Controller
 */

class App_User_Controller_Frontend extends RCMS_Controller {
	public function Action_Index() {
        Rev1lZ_HTTP::redirect(SITE_PATH);
	}

    public function Action_Auth() {
        if (isset($_POST["login"], $_POST["password"])) {
            $auth = $this->_user->externalAuth($_POST["login"], $_POST["password"]);

            if ($auth->code = 0 && !$this->_ajax)
                Rev1lZ_HTTP::redirect(SITE_PATH);
            else
                $this->_view->alert($auth->type, $auth->message);
        }

        $auth = new App_User_Model_Auth();

        $this->_core
            ->setTitle($auth->title)
            ->addBreadcrumbs($auth->title);

        $this->_view
            ->add($auth->viewName)
            ->render($auth->viewName);
    }
}