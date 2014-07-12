<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Registry class
 */

class RCMS_Registry {
    /**
     * @var object
     */
    private static $_instance;

    /**
     * @var array
     */
    private $_objects = array ();

    /**
     * @return RCMS_Registry
     *
     * Get Object Instance
     */
    public static function getInstance() {
        if (empty(self::$_instance))
            self::$_instance = new self;

        return self::$_instance;
    }

    /**
     * @param $key
     * @param $object
     * @return $this
     * @throws Exception
     *
     * Add Object to Stack
     */
    public function addObject($key, $object) {
        if (array_key_exists($key, $this->_objects))
            throw new Exception("Object {$key} already registered");

        $this->_objects[$key] = $object;

        return $this;
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     *
     * Get Object from Stack
     */
    public function getObject($key) {
        if (!array_key_exists($key, $this->_objects)) {
            throw new Exception("Object {$key} is not registered");
        }

        return $this->_objects[$key];
    }
}