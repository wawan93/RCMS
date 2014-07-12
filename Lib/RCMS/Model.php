<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Model abstract class
 */

abstract class RCMS_Model {
    public $code = 0;
    public $title = "";
    public $viewName = "";
    public $render = null;

    public $type = "";
    public $message = "";

    public $tags = array ();
    public $blocks = array ();

    protected $config;
    protected $lang;
    protected $core;
    protected $db;
    protected $view;
    protected $user;

	public function __construct () {
        $registry = RCMS_Registry::getInstance();

		$this->config = $registry->getObject("Config");
        $this->lang = $registry->getObject("Lang");
        $this->core = $registry->getObject("Core");
		$this->db =$registry->getObject("Database");
		$this->view = $registry->getObject("View");
        $this->user = $registry->getObject("User");
	}
}