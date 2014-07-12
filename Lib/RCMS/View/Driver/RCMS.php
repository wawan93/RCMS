<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS View Driver RCMS class
 */

class RCMS_View_Driver_RCMS extends RCMS_View_Driver {
    /**
     * @return array
     */
    private function _tags() {
		return array (
			"PATH" => SITE_PATH,
            "SELF" => $_SERVER["REQUEST_URI"],
            "VIEW" => $this->_config->get(0, "Site", "Path") . "view/" . $this->_config->get(0, "View", "View") . "/public/",
			"MODULE" => $this->_router->getModule(),
			"ACTION" => $this->_router->getAction()
		);
	}

    /**
     * @return array
     */
    private function _blocks() {
		return array (
			"logged" => $this->_user->isLogged(),
			"not-logged" => !$this->_user->isLogged()
		);
	}

    /**
     * @var object
     */
    private $_core;

    /**
     * @var object
     */
    private $_config;

    /**
     * @var object
     */
    private $_lang;

    /**
     * @var object
     */
    private $_router;

    /**
     * @var object
     */
    private $_user;

    /**
     * @var array
     */
    private $_stack = array (
		"Alert" => ""
	);

	public function __construct () {
        $registry = RCMS_Registry::getInstance();

        $this->_core = $registry->getObject("Core");
		$this->_config = $registry->getObject("Config");
        $this->_lang = $registry->getObject("Lang");
		$this->_router = $registry->getObject("Router");
        $this->_user = $registry->getObject("User");
	}

    /**
     * @param $args
     * @return string
     */
    private function ifBlock($args) {
		$tags = $this->_tags();

		if (isset($tags[$args[1]]) && $tags[$args[1]] == $args[2])
			return $args[3];
		else
			return "";
	}

    /**
     * @param $block
     * @param $show
     * @param $view
     * @return mixed
     */
    private function _block($block, $show, $view) {
		if ($show) {
			$view = str_replace("[{$block}]", "", $view);
			$view = str_replace("[/{$block}]", "", $view);
		} else {
			$block = "#\\[{$block}\\](.*?)\\[/{$block}\\]#is";
			$view = preg_replace($block, "", $view);
		}

		return $view;
	}

	private function _tag($tag, $value, $view) {
		return str_replace("{" . $tag . "}", $value, $view);
	}

    /**
     * @param $name
     * @param array $tags
     * @param array $blocks
     * @return $this
     * @throws Exception
     */
    public function add($name, $tags = array(), $blocks = array()) {
		$file = VIEW . DS . $this->_config->get(0, "View", "View") . DS . $name . ".html";

		if (!file_exists($file))
			throw new Exception("Couldn't find view: " . $name);
		else {
			$view = file_get_contents($file);

			if ($view === false)
				throw new Exception("Couldn't load view: " . $name);
			else {
				$view = preg_replace_callback("#\\[if \{(.+)\}=\"(.*)\"\\](.*)\\[/if\\]#is", array(&$this, "ifBlock"), $view);

				$blocks = array_merge($blocks, $this->_blocks());
				$tags = array_merge($tags, $this->_tags());

				foreach ($blocks as $block => $show)
					$view = $this->_block($block, $show, $view);

				foreach ($tags as $tag => $value)
					$view = $this->_tag($tag, $value, $view);

				if (isset($this->_stack[$name]))
					$this->_stack[$name] .= $view;
				else
					$this->_stack[$name] = $view;

				return $this;
			}
		}
	}

    /**
     * @param $type
     * @param $message
     * @return $this
     */
    public function alert($type, $message) {
		$this->add("Alert", array (
			"type" => $type,
			"message" => $message
		));

		return $this;
	}

    /**
     * @param $stack
     * @return bool
     */
    public function get($stack) {
		return isset($this->_stack[$stack]) ? $this->_stack[$stack] : false;
	}

    /**
     * @param null $stack
     * @param null $mainView
     */
    public function render($stack = null, $mainView = null) {
        if ($mainView === null)
            $mainView = $this->_config->get(0, "View", "Layout");

        $path = SITE_PATH;

        $loadingLayer = $this->_lang->get(0, "Ajax", "loadingLayer");
        $unknownError = $this->_lang->get(0, "Ajax", "unknownError");

        $ajax = <<<HTML
<div id="cms_alert"></div>
<div id="cms_loading-layer"></div>

<script type="text/javascript">
  rcms.path = '{$path}';

  lang.core.loadingLayer = '{$loadingLayer}';
  lang.core.unknownError = '{$unknownError}';
</script>
HTML;


        $tags = array (
			"title" => $this->_core->getTitle(),

            "name" => $this->_config->get(0, "Site", "Name"),

            "meta" => $this->_core->getMeta(),
			"css" => $this->_core->getCss(),
			"js" => $this->_core->getJs(),
            "ajax" => $ajax,

            "breadcrumbs" => $this->_core->getBreadcrumbs(),
			"alerts" => $this->_stack["Alert"],
			"content" => isset($this->_stack[$stack]) ? $this->_stack[$stack] : ""
		);

		if ($this->_user->isLogged()) {
			$tags["profile-link"] = $path . "User/Profile/" . $this->_user->get("login");
			$tags["logout-link"] = $path . "User/Logout";
		} else {
			$tags["auth-link"] = $path . "User/Auth";
			$tags["register-link"] = $path . "User/Register";
		}

		$this->add($mainView, $tags);

		echo $this->get($mainView);
	}
}