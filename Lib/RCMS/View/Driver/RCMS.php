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
    private function tags() {
		return array (
			"PATH" => SITE_PATH,
            "SELF" => $_SERVER["REQUEST_URI"],
            "VIEW" => $this->config->get(0, "Site", "Path") . "view/" . $this->config->get(0, "View", "View") . "/public/",
			"MODULE" => $this->router->getModule(),
			"ACTION" => $this->router->getAction()
		);
	}

    /**
     * @return array
     */
    private function blocks() {
		return array (
			"logged" => $this->user->isLogged(),
			"not-logged" => !$this->user->isLogged()
		);
	}

    /**
     * @var object
     */
    private $core;

    /**
     * @var object
     */
    private $config;

    /**
     * @var object
     */
    private $lang;

    /**
     * @var object
     */
    private $router;

    /**
     * @var object
     */
    private $user;

    /**
     * @var array
     */
    private $stack = array (
		"Alert" => ""
	);

    /**
     * @var string
     */
    private $title = "";

	public function __construct () {
        $registry = RCMS_Registry::getInstance();

        $this->core = $registry->getObject("Core");
		$this->config = $registry->getObject("Config");
        $this->lang = $registry->getObject("Lang");
		$this->router = $registry->getObject("Router");
        $this->user = $registry->getObject("User");
	}

    /**
     * @param $args
     * @return string
     */
    private function ifBlock($args) {
		$tags = $this->tags();

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
    private function block($block, $show, $view) {
		if ($show) {
			$view = str_replace("[{$block}]", "", $view);
			$view = str_replace("[/{$block}]", "", $view);
		} else {
			$block = "#\\[{$block}\\](.*?)\\[/{$block}\\]#is";
			$view = preg_replace($block, "", $view);
		}

		return $view;
	}

	private function tag($tag, $value, $view) {
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
		$file = VIEW . DS . $this->config->get(0, "View", "View") . DS . $name . ".html";

		if (!file_exists($file))
			throw new Exception("Couldn't find view: " . $name);
		else {
			$view = file_get_contents($file);

			if ($view === false)
				throw new Exception("Couldn't load view: " . $name);
			else {
				$view = preg_replace_callback("#\\[if \{(.+)\}=\"(.*)\"\\](.*)\\[/if\\]#is", array(&$this, "ifBlock"), $view);

				$blocks = array_merge($blocks, $this->blocks());
				$tags = array_merge($tags, $this->tags());

				foreach ($blocks as $block => $show)
					$view = $this->block($block, $show, $view);

				foreach ($tags as $tag => $value)
					$view = $this->tag($tag, $value, $view);

				if (isset($this->stack[$name]))
					$this->stack[$name] .= $view;
				else
					$this->stack[$name] = $view;

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
		return isset($this->stack[$stack]) ? $this->stack[$stack] : false;
	}

    /**
     * @param null $stack
     * @param null $mainView
     */
    public function render($stack = null, $mainView = null) {
        if ($mainView === null)
            $mainView = $this->config->get(0, "View", "Layout");

        $path = SITE_PATH;

        $loadingLayer = $this->lang->get(0, "Ajax", "loadingLayer");
        $unknownError = $this->lang->get(0, "Ajax", "unknownError");

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
			"title" => $this->core->getTitle(),

            "name" => $this->config->get(0, "Site", "Name"),

            "meta" => $this->core->getMeta(),
			"css" => $this->core->getCss(),
			"js" => $this->core->getJs(),
            "ajax" => $ajax,

            "breadcrumbs" => $this->core->getBreadcrumbs(),
			"alerts" => $this->stack["Alert"],
			"content" => isset($this->stack[$stack]) ? $this->stack[$stack] : ""
		);

		if ($this->user->isLogged()) {
			$tags["profile-link"] = $path . "User/Profile/" . $this->user->get("login");
			$tags["logout-link"] = $path . "User/Logout";
		} else {
			$tags["auth-link"] = $path . "User/Auth";
			$tags["register-link"] = $path . "User/Register";
		}

		$this->add($mainView, $tags);

		echo $this->get($mainView);
	}
}