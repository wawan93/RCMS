<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Core class
 */

class RCMS_Core {
    /**
     * @var object
     */
    private $config = null;

    /**
     * @var object
     */
    private $lang = null;

    /**
     * @var string
     */
    private $title = "";

    /**
     * @var array
     */
    private $meta = array ();

    /**
     * @var array
     */
    private $css = array ();

    /**
     * @var array
     */
    private $js = array ();

    /**
     * @var array
     */
    private $breadcrumbs = array ();

    public function __construct() {
        $registry = RCMS_Registry::getInstance();

        $this->config = $registry->getObject("Config");
        $this->lang = $registry->getObject("Lang");
    }

    /**
     * @param string $title
     * @return $this
     *
     * Set page title
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     *
     * Get page title
     */
    public function getTitle() {
        return empty($this->title) ? "Неизвестная страница" : $this->title;
    }

    /**
     * @param string $name
     * @param string $content
     * @return $this
     * @throws Exception
     *
     * Add page meta
     */
    public function addMeta($name, $content) {
        if (array_search($name, $this->meta) === false)
            $this->meta[$name] = $content;
        else
            throw new Exception("Core error: Meta {$name} already exists!");

        return $this;
    }

    /**
     * @param array $array
     * @return $this;
     *
     * Add page meta by Array
     */
    public function addMetaArray($array) {
        foreach ($array as $name => $content) {
            $this->addMeta($name, $content);
        }

        return $this;
    }

    /**
     * @return string
     *
     * Get page meta
     */
    public function getMeta() {
        $html = "";

        foreach($this->meta as $name => $content)
            $html .= "<meta name=\"{$name}\" content=\"{$content}\">\n";

        return $html;
    }

    /**
     * @param string $src
     * @return $this
     * @throws Exception
     *
     * Add page CSS
     */
    public function addCss($src) {
        if (!in_array($src, $this->css))
            $this->css[] = SITE_PATH . $src;
        else
            throw new Exception("Core error: Css {$src} already exists!");

        return $this;
    }

    /**
     * @param array $array
     * @return $this
     *
     * Add page CSS by Array
     */
    public function addCssArray(array $array) {
        foreach ($array as $src) {
            $this->addCss($src);
        }

        return $this;
    }

    /**
     * @return string
     *
     * Get page CSS
     */
    public function getCss() {
        $html = "";

        foreach($this->css as $src)
            $html .= "<link href=\"{$src}\" type=\"text/css\" rel=\"stylesheet\">\n";

        return $html;
    }

    /**
     * @param string $src
     * @return $this
     * @throws Exception
     *
     * Add page JS
     */
    public function addJs($src) {
        if (!in_array($src, $this->js))
            $this->js[] = SITE_PATH . $src;
        else
            throw new Exception("Core error: Js {$src} already exists!");

        return $this;
    }

    /**
     * @param array $array
     * @return $this
     *
     * Add page JS by Array
     */
    public function addJsArray(array $array) {
        foreach ($array as $src) {
            $this->addJs($src);
        }

        return $this;
    }

    /**
     * @return string
     *
     * Get page JS
     */
    public function getJs() {
        $html = "";

        foreach($this->js as $src)
            $html .= "<script src=\"{$src}\"></script>\n";

        return $html;
    }

    /**
     * @param $name
     * @param null $link
     * @return $this
     *
     * Add page Breadcrumbs
     */
    public function addBreadcrumbs($name, $link = null) {
        $this->breadcrumbs[] = array (
            $name, $link
        );

        return $this;
    }

    /**
     * @param array $array
     * @return $this
     *
     * Add page Breadcrumbs by Array
     */
    public function addBreadcrumbsArray(array $array) {
        foreach ($array as $name => $link)
            $this->addBreadcrumbs($name, $link);

        return $this;
    }

    /**
     * @return string
     *
     * Get page Breadcrumbs
     */
    public function getBreadcrumbs() {
        $path = SITE_PATH;
        $html = "";

        foreach ($this->breadcrumbs as $row) {
            $active = ($row == end($this->breadcrumbs)) ? " class=\"active\"" : "";
            $name = ($row[1] === null) ? $row[0] : "<a href=\"{$path}{$row[1]}\">{$row[0]}</a>";
            $html .= "<li{$active}>{$name}</li>";
        }

        return $html;
    }

    /**
     * @param $timestamp
     * @param bool $smart
     * @return string
     *
     * Get date in format by timestamp
     */
    public function getDate($timestamp, $smart = true) {
        if ($smart && $this->config->get(0, "Core", "SmartDate"))
            if (date("j") == date("j", $timestamp))
                return $this->lang->get(0, "Core", "smartDate.today");
            else if (date("j") == date("j", strtotime("+1 day", $timestamp)))
                return $this->lang->get(0, "Core", "smartDate.yesterday");
            else
                return date($this->config->get(0, "Format", "Date"), $timestamp);
        else
            return date($this->config->get(0, "Format", "Date"), $timestamp);
    }

    /**
     * @param $timestamp
     * @return bool|string
     *
     * Get time in format by timestamp
     */
    public function getTime($timestamp) {
        return date($this->config->get(0, "Format", "Time"), $timestamp);
    }

    /**
     * @param $timestamp
     * @return bool|string
     *
     * Get datetime in iso format by timestamp
     */
    public static function getISODatetime($timestamp) {
        return date("c", $timestamp);
    }
}