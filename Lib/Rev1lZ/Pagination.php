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
    private $_numRows;

    /**
     * @var int
     */
    private $_numPages;

    /**
     * @var int
     */
    private $_currentPage;

    /**
     * @var string
     */
    private $_prefix;

    /**
     * @var array
     */
    private $_config = array (
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
        $this->_numRows = intval($numRows);
        $this->_currentPage = $currentPage;
        $this->_prefix = $prefix;

        foreach ($customConfig as $key => $value)
            $this->_config[$key] = $value;

        $numPages = ceil($this->_numRows / $this->_config["rowsOnPage"]);

        if ($numPages > 0)
            $this->_numPages = $numPages;
        else
            $this->_numPages = 1;
                
        if ($currentPage > 0 && $currentPage <= $this->_numPages)
            $this->_currentPage = $currentPage;
        else
            $this->_currentPage = 1;
    }

    /**
     * @return string
     *
     * Get limits for SQL query
     */
    public function getSqlLimits () {
        $minRow = ($this->_currentPage - 1) * $this->_config["rowsOnPage"];
        $maxRow = $this->_config["rowsOnPage"];

        return array (
            $minRow, $maxRow
        );
    }

    /**
     * @param $id
     * @param $link
     * @return string
     */
    private function _getRow ($id, $link) {
        if ($link === true) {
            $pageUrl = $this->_prefix;
            $thisPage = ($this->_currentPage == $id);
            
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
        if ($this->_numPages > 1) {
            $class = $this->_config["htmlClass"];

            $html = <<<HTML
    <ul class="{$class}">
HTML;
            $minPage = $this->_currentPage-4;
            $maxPage = $this->_currentPage+4;

            $html .= $this->_getRow(1, true);

            if ($minPage > 2)
                $html .= $this->_getRow("...", false);
            else
                $minPage = 2;

            if ($maxPage >= $this->_numPages - 1)
                $maxPage = $this->_numPages - 1;

            for ($i = $minPage; $i <= $maxPage; $i++)
                $html .= $this->_getRow($i, true);

            if ($maxPage < $this->_numPages - 1)
                $html .= $this->_getRow("...", false);

            $html .= $this->_getRow($this->_numPages, true);

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