<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * Rev1lZ Pagination class
 */

class Rev1lZ_Pagination {
    /**
     * @var int
     */
    private $numRows;

    /**
     * @var int
     */
    private $numPages;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var array
     */
    private $config = array (
        "rowsOnPage" => 10,
        "htmlClass" => "cms-pagination"
    );

    /**
     * @param $numRows
     * @param $currentPage
     * @param $prefix
     * @param array $customConfig
     */
    public function __construct ($numRows, $currentPage,  $prefix, $customConfig = array()) {
        $this->numRows = intval($numRows);
        $this->currentPage = $currentPage;
        $this->prefix = $prefix;

        foreach ($customConfig as $key => $value)
            $this->config[$key] = $value;

        $numPages = ceil($this->numRows / $this->config["rowsOnPage"]);

        if ($numPages > 0)
            $this->numPages = $numPages;
        else
            $this->numPages = 1;
                
        if ($currentPage > 0 && $currentPage <= $this->numPages)
            $this->currentPage = $currentPage;
        else
            $this->currentPage = 1;
    }

    /**
     * @return string
     *
     * Get limits for SQL query
     */
    public function getSqlLimits () {
        $minRow = ($this->currentPage - 1) * $this->config["rowsOnPage"];
        $maxRow = $this->config["rowsOnPage"];

        return array (
            $minRow, $maxRow
        );
    }

    /**
     * @param $id
     * @param $link
     * @return string
     */
    private function getRow ($id, $link) {
        if ($link === true) {
            $pageUrl = $this->prefix;
            $thisPage = ($this->currentPage == $id);
            
            $currentLi = $thisPage ? " class=\"active\"" : "";
            $currentA = $thisPage ? " class=\"active\"" : "";

            return <<<HTML
      <li{$currentLi}>
        <a{$currentA} href="{$pageUrl}{$id}">{$id}</a>
      </li>
HTML;
        } else {
            return <<<HTML
      <li class="disabled">
        <a class="disabled">{$id}</a>
      </li>
HTML;
        }
    }

    /**
     * @return string
     *
     * Get Pagination HTML Code
     */
    public function getHtmlCode () {
        if ($this->numPages > 1) {
            $class = $this->config["htmlClass"];

            $html = <<<HTML
    <ul class="{$class}">
HTML;
            $minPage = $this->currentPage-4;
            $maxPage = $this->currentPage+4;

            $html .= $this->getRow(1, true);

            if ($minPage > 2)
                $html .= $this->getRow("...", false);
            else
                $minPage = 2;

            if ($maxPage >= $this->numPages - 1)
                $maxPage = $this->numPages - 1;

            for ($i = $minPage; $i <= $maxPage; $i++)
                $html .= $this->getRow($i, true);

            if ($maxPage < $this->numPages - 1)
                $html .= $this->getRow("...", false);

            $html .= $this->getRow($this->numPages, true);

            $html .= <<<HTML
    </ul>
HTML;

            return $html;
        } else
            return "";
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getHtmlCode();
    }
}