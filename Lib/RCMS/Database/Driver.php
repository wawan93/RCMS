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
    protected $sql = array ();

    /**
     * @var object|resource
     */
    protected $db;

    const SELECT_OPERATOR       = "SELECT",

        FROM_OPERATOR           = "FROM",

        WHERE_OPERATOR          = "WHERE",

        AND_OPERATOR            = "AND",

        OR_OPERATOR             = "OR",

        ORDER_OPERATOR          = "ORDER",

        BY_OPERATOR             = "BY",

        DESC_OPERATOR           = "DESC",

        ASC_OPERATOR            = "ASC",

        JOIN_OPERATOR           = "JOIN",

        ON_OPERATOR             = "ON",

        DELETE_OPERATOR         = "DELETE",

        NOT_OPERATOR            = "NOT",

        IN_OPERATOR             = "IN",

        AS_OPERATOR             = "AS",

        LIKE_OPERATOR           = "LIKE",

        LIMIT_OPERATOR          = "LIMIT",

        INSERT_OPERATOR         = "INSERT",

        INTO_OPERATOR           = "INTO",

        VALUES_OPERATOR         = "VALUES",

        SET_OPERATOR            = "SET",

        UPDATE_OPERATOR         = "UPDATE",

        REPLACE_OPERATOR        = "REPLACE",

        INNER_OPERATOR          = "INNER",

        FULL_OPERATOR           = "FULL",

        LEFT_OPERATOR           = "LEFT",

        RIGHT_OPERATOR          = "RIGHT",

        CROSS_OPERATOR          = "CROSS",

        ANY_OPERATOR            = "ANY";

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
        $this->sql[] = $sql;

        return $this;
    }

    /**
     * @param $into
     * @return $this
     */
    public function replace_into($into) {
        $this->sql[] = self::REPLACE_OPERATOR . " " . self::INTO_OPERATOR . " " . $into;

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

        $this->sql[] = self::SELECT_OPERATOR . " " . $select;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function from($table) {
        $this->sql[] = self::FROM_OPERATOR . " " . $table;

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
            $what = self::ANY_OPERATOR;

        $this->sql[] = self::WHERE_OPERATOR . " " . $that . " " . $symbol . " " . $what;

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
            $what = self::ANY_OPERATOR;

        $this->sql[] = self::AND_OPERATOR . " " . $that . " " . $symbol . " " . $what;

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
            $what = self::ANY_OPERATOR;

        $this->sql[] = self::OR_OPERATOR . " " . $that . " " . $symbol . " " . $what;

        return $this;
    }

    /**
     * @param string $by
     * @return $this
     */
    public function order_by($by) {
        $this->sql[] = self::ORDER_OPERATOR . " " . self::BY_OPERATOR . " " . $by;

        return $this;
    }

    /**
     * @return $this
     */
    public function desc() {
        $this->sql[] = self::DESC_OPERATOR;

        return $this;
    }

    /**
     * @return $this
     */
    public function asc() {
        $this->sql[] = self::ASC_OPERATOR;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function insert_into($table) {
        $this->sql[] = self::INSERT_OPERATOR . " " . self::INTO_OPERATOR . " " . $table;

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

        $this->sql[] = "(" . implode(", ", $_rows) . ") " . self::VALUES_OPERATOR . " (" . implode(", ", $_values) . ")";

        return $this;
    }

    /**
     * @param array $limit
     * @return $this
     */
    public function limit(Array $limit) {
        $this->sql[] = self::LIMIT_OPERATOR . " " . $limit[0] . ", " . $limit[1] ;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function update($table) {
        $this->sql[] = self::UPDATE_OPERATOR . " " . $table;

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

        $this->sql[] = self::SET_OPERATOR . " " . implode(", ", $s);

        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function delete($table) {
        $this->sql[] = self::DELETE_OPERATOR . " " . $table;

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
        return $this->db;
    }

    /**
     * @return string
     */
    public function getSql() {
        $sql = implode(" ", $this->sql);

        unset($this->sql);

        return $sql;
    }
}