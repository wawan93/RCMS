<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Database Driver MySQLi class
 */

class RCMS_Database_Driver_MySQLi extends RCMS_Database_Driver {
    /**
     * @var object
     */
    private $_db;

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
        if (!$this->_db = mysqli_connect($host, $username, $password, $name))
            throw new Exception('Database error: connect error, ' . __METHOD__);

        if (!mysqli_set_charset($this->_db, $charset))
            throw new Exception('Database error: charset error, ' . __METHOD__);
    }

    /**
     * @return int
     */
    public function insert_id() {
        return mysqli_insert_id($this->_db);
    }

    /**
     * @param @string
     * @return string
     */
    public function safe($string) {
        return mysqli_real_escape_string($this->_db, $string);
    }

    /**
     * @return mixed|string
     */
    public function getError() {
        return mysqli_error($this->_db);
    }

    /**
     * @return bool
     */
    public function result() {
        if (mysqli_query($this->_db, $this->getSql()))
            return true;
        else
            return false;
    }

    /**
     * @return int
     */
    public function result_num() {
        $num = mysqli_num_rows(mysqli_query($this->_db, $this->getSql()));

        if ($num === false)
            return false;
        else
            return $num;
    }

    /**
     * @return array
     */
    public function result_array() {
        if ($query = mysqli_query($this->_db, $this->getSql())) {
            $result = array ();

            while ($row = mysqli_fetch_array($query))
                $result[] = $row;

            return $result;
        }

        return false;
    }

    /**
     * @return void
     */
    public function close() {
        mysqli_close($this->_db);
    }
}