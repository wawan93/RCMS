<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Router class
 */

class RCMS_Router {
	private $routes = array ();

	private $module;
	private $action;

	private $config;
    private $lang;

    public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->config = $registry->getObject("Config");
        $this->lang = $registry->getObject("Lang");

        $this->routes = explode("/", isset($_GET["go"]) ? $_GET["go"] : "");
        $this->module = $this->get(0) ? $this->get(0) : $this->config->get(0, "Defaults", "Module");
        $this->action = $this->get(1) ? $this->get(1) : "Index";

        $this->config->addStack(1, $this->module);
        $this->lang->addStack(1, $this->module);
    }

    /**
     * @param $id
     * @return mixed
     *
     * Get Route by id
     */
    public function get($id) {
        return isset($this->routes[$id]) ? $this->routes[$id] : false;
    }

    /**
     * @return string
     *
     * Get Module name
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @return string
     *
     * Get Action name
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Start router
     */
    public function start() {
        $type = "Frontend";

		$this->check_module($this->module, $type);

		$controller_class = "App_" . $this->module . "_Controller_" . $type;

		$controller = new $controller_class;
		$action = "Action_" . (($controller->default_actions) ? $this->action : "Index");

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