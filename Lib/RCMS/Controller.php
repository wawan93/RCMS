<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Controller abstract class
 */

abstract class RCMS_Controller {
    protected $ajax;

	protected $config;
    protected $lang;
    protected $core;
    protected $view;
    protected $user;
    protected $router;

    public $default_actions = true;

	public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->ajax = defined("AJAX");

        $this->config = $registry->getObject("Config");
        $this->lang = $registry->getObject("Lang");
        $this->core = $registry->getObject("Core");
		$this->view = $registry->getObject("View");
        $this->user = $registry->getObject("User");
        $this->router = $registry->getObject("Router");
	}

    public abstract function Action_Index();
}