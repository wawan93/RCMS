<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Controller abstract class
 */

abstract class RCMS_Controller {
    protected $_ajax;

	protected $_config;
    protected $_lang;
    protected $_core;
    protected $_view;
    protected $_user;
    protected $_router;

    public $default_actions = true;

	public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->_ajax = defined("AJAX");

        $this->_config = $registry->getObject("Config");
        $this->_lang = $registry->getObject("Lang");
        $this->_core = $registry->getObject("Core");
		$this->_view = $registry->getObject("View");
        $this->_user = $registry->getObject("User");
        $this->_router = $registry->getObject("Router");
	}

    public abstract function Action_Index();
}