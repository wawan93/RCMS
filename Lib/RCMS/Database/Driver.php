<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Database Driver abstract class
 */

abstract class RCMS_Database_Driver {
    /**
     * @var array
     */
    protected $_sql = array ();

    /**
     * @var object|resource
     */
    protected $_db;

    const _SELECT_OPERATOR       = "SELECT",

        _FROM_OPERATOR           = "FROM",

        _WHERE_OPERATOR          = "WHERE",

        _AND_OPERATOR            = "AND",

        _OR_OPERATOR             = "OR",

        _ORDER_OPERATOR          = "ORDER",

        _BY_OPERATOR             = "BY",

        _DESC_OPERATOR           = "DESC",

        _ASC_OPERATOR            = "ASC",

        _JOIN_OPERATOR           = "JOIN",

        _ON_OPERATOR             = "ON",

        _DELETE_OPERATOR         = "DELETE",

        _NOT_OPERATOR            = "NOT",

        _IN_OPERATOR             = "IN",

        _AS_OPERATOR             = "AS",

        _LIKE_OPERATOR           = "LIKE",

        _LIMIT_OPERATOR          = "LIMIT",

        _INSERT_OPERATOR         = "INSERT",

        _INTO_OPERATOR           = "INTO",

        _VALUES_OPERATOR         = "VALUES",

        _SET_OPERATOR            = "SET",

        _UPDATE_OPERATOR         = "UPDATE",

        _REPLACE_OPERATOR        = "REPLACE",

        _INNER_OPERATOR          = "INNER",

        _FULL_OPERATOR           = "FULL",

        _LEFT_OPERATOR           = "LEFT",

        _RIGHT_OPERATOR          = "RIGHT",

        _CROSS_OPERATOR          = "CROSS",

        _ANY_OPERATOR            = "ANY";

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $charset
     * @return mixed
     */
    abstract function connect($host, $username, $password, $name, $charset = "utf8");

    /**
     * @param string $sql sql query
     * @return $this
     */
    public function query($sql) {
        $this->_sql[] = $sql;

        return $this;
    }

    /**
     * @param $into
     * @return $this
     */
    public function replace_into($into) {
        $this->_sql[] = self::_REPLACE_OPERATOR . " " . self::_INTO_OPERATOR . " " . $into;

        return $this;
    }

    /**
     * @param $args
     * @return $this
     */
    public function select($args) {
        $select = "";

        if (is_array($args))
                $select .= implode(", ", $args);
        else
            $select = $args;

        $this->_sql[] = self::_SELECT_OPERATOR . " " . $select;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function from($table) {
        $this->_sql[] = self::_FROM_OPERATOR . " " . $table;

        return $this;
    }

    /**
     * @param string $that
     * @param string $symbol
     * @param string $what
     * @return $this
     */
    public function where($that, $symbol, $what = "") {
        if(empty($what))
            $what = self::_ANY_OPERATOR;

        $this->_sql[] = self::_WHERE_OPERATOR . " " . $that . " " . $symbol . " " . $what;

        return $this;
    }

    /**
     * @param string $that
     * @param string $symbol
     * @param string $what
     * @return $this
     */
    public function and_where($that, $symbol, $what) {
        if(empty($what))
            $what = self::_ANY_OPERATOR;

        $this->_sql[] = self::_AND_OPERATOR . " " . $that . " " . $symbol . " " . $what;

        return $this;
    }

    /**
     * @param string $that
     * @param string $symbol
     * @param string $what
     * @return $this
     */
    public function or_where($that, $symbol, $what) {
        if(empty($what))
            $what = self::_ANY_OPERATOR;

        $this->_sql[] = self::_OR_OPERATOR . " " . $that . " " . $symbol . " " . $what;

        return $this;
    }

    /**
     * @param string $by
     * @return $this
     */
    public function order_by($by) {
        $this->_sql[] = self::_ORDER_OPERATOR . " " . self::_BY_OPERATOR . " " . $by;

        return $this;
    }

    /**
     * @return $this
     */
    public function desc() {
        $this->_sql[] = self::_DESC_OPERATOR;

        return $this;
    }

    /**
     * @return $this
     */
    public function asc() {
        $this->_sql[] = self::_ASC_OPERATOR;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function insert_into($table) {
        $this->_sql[] = self::_INSERT_OPERATOR . " " . self::_INTO_OPERATOR . " " . $table;

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function values(Array $values) {
        $_rows = Array();
        $_values = Array();

        foreach($values as $row => $value) {
            $_rows[] = $row;
            $_values[] = $value;
        }

        $this->_sql[] = "(" . implode(", ", $_rows) . ") " . self::_VALUES_OPERATOR . " (" . implode(", ", $_values) . ")";

        return $this;
    }

    /**
     * @param array $limit
     * @return $this
     */
    public function limit(Array $limit) {
        $this->_sql[] = self::_LIMIT_OPERATOR . " " . $limit[0] . ", " . $limit[1] ;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function update($table) {
        $this->_sql[] = self::_UPDATE_OPERATOR . " " . $table;

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function set(Array $values) {
        $s = Array();

        foreach($values as $row => $value)
            $s[] = $row . "=" . $value;

        $this->_sql[] = self::_SET_OPERATOR . " " . implode(", ", $s);

        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function delete($table) {
        $this->_sql[] = self::_DELETE_OPERATOR . " " . $table;

        return $this;
    }

    /**
     * @return int
     */
    abstract function insert_id();

    /**
     * @param $string
     * @return string
     */
    abstract function safe($string);

    /**
     * @param $string
     * @return string
     *
     * Safe string for use in sql
     */
    function string($string) {
        return "'" . $this->safe($string) . "'";
    }

    /**
     * @return mixed|string
     */
    abstract function getError();

    /**
     * @return bool
     */
    abstract function result();

    /**
     * @return int
     */
    abstract function result_num();

    /**
     * @return array
     */
    abstract function result_array();

    /**
     * @return bool
     */
    abstract function close();

    /**
     * @return object|resource
     */
    public function getLink() {
        return $this->_db;
    }

    /**
     * @return string
     */
    public function getSql() {
        $sql = implode(" ", $this->_sql);

        unset($this->sql);

        return $sql;
    }
}