<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Router class
 */

class RCMS_Router {
	private $routes = array ();

	private $_module;
	private $_action;

	private $_config;
    private $_lang;

    public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->_config = $registry->getObject("Config");
        $this->_lang = $registry->getObject("Lang");

        $this->_routes = explode("/", isset($_GET["go"]) ? $_GET["go"] : "");
        $this->_module = $this->get(0) ? $this->get(0) : $this->_config->get(0, "Defaults", "Module");
        $this->_action = $this->get(1) ? $this->get(1) : "Index";

        $this->_config->addStack(1, $this->_module);
        $this->_lang->addStack(1, $this->_module);
    }

    /**
     * @param $id
     * @return mixed
     *
     * Get Route by id
     */
    public function get($id) {
        return isset($this->_routes[$id]) ? $this->_routes[$id] : false;
    }

    /**
     * @return string
     *
     * Get Module name
     */
    public function getModule() {
        return $this->_module;
    }

    /**
     * @return string
     *
     * Get Action name
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * Start router
     */
    public function start() {
        $type = "Frontend";

		$this->check_module($this->_module, $type);

		$controller_class = "App_" . $this->_module . "_Controller_" . $type;

		$controller = new $controller_class;
		$action = "Action_" . (($controller->default_actions) ? $this->_action : "Index");

		if (method_exists($controller, $action))
			$controller->$action();
		else
			$this->page_404();
	}

	private function check_module($name, $type) {
		$path = LIB . DS . "App" . DS . $name . DS . "Controller" . DS . $type . EXT;

		if (!file_exists($path))
			$this->page_404();
	}

    /**
     * Render 404 page
     */
    public function page_404() {
        RCMS_Registry::getInstance()
            ->getObject("View")
            ->render(null, "404");
        exit;
    }
}