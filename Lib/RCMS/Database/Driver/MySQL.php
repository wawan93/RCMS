<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Database Driver MySQL class
 */

class RCMS_Database_Driver_MySQL extends RCMS_Database_Driver {
    /**
     * @param $host
     * @param $username
     * @param $password
     * @param $name
     * @param string $charset
     * @return mixed|void
     * @throws Exception
     */
    public function connect($host, $username, $password, $name, $charset = 'utf8') {
        if (!$this->_db = mysql_connect($host, $username, $password))
            throw new Exception('Database error: connect error, ' . __METHOD__);

        if (!mysql_select_db($name, $this->_db))
            throw new Exception('Database error: select database error, ' . __METHOD__);

        if (!mysql_set_charset($charset, $this->_db))
            throw new Exception('Database error: set charset error, ' . __METHOD__);
    }

    /**
 * @return int
 */
    public function insert_id() {
        return mysql_insert_id($this->_db);
    }

    /**
     * @param @string
     * @return string
     */
    public function safe($string) {
        return mysql_real_escape_string($string, $this->_db);
    }

    /**
     * @return mixed|string
     */
    public function getError() {
        return mysql_error($this->_db);
    }

    /**
     * @return bool
     */
    public function result() {
        if (mysql_query($this->getSql(), $this->_db))
            return true;
        else
            return false;
    }

    /**
    * @return int
    */
    public function result_num() {
        $num = mysql_num_rows(mysql_query($this->getSql(), $this->_db));

        if ($num === false)
            return false;
        else
            return $num;
    }

    /**
     * @return array
     */
    public function result_array() {
        if ($query = mysql_query($this->getSql(), $this->_db)) {
            $result = array ();

            while ($row = mysql_fetch_array($query))
                $result[] = $row;

            return $result;
        }

        return false;
    }

    public function close() {
        mysql_close($this->_db);
    }
}