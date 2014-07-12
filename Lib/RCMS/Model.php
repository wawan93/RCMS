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

    protected $_config;
    protected $_lang;
    protected $_core;
    protected $_db;
    protected $_view;
    protected $_user;

	public function __construct () {
        $registry = RCMS_Registry::getInstance();

		$this->_config = $registry->getObject("Config");
        $this->_lang = $registry->getObject("Lang");
        $this->_core = $registry->getObject("Core");
		$this->_db =$registry->getObject("Database");
		$this->_view = $registry->getObject("View");
        $this->_user = $registry->getObject("User");
	}
}