<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Database Main class
 */

class RCMS_Database_Main {
    /**
     * @var object
     */
    protected static $_instance;

    /**
     * @var string
     */
    protected $_driver_class_prefix = "RCMS_Database_Driver_";

    /**
     * @return object
     */
    public static function getInstance() {
        if (empty(self::$_instance))
            self::$_instance = new self;

        return self::$_instance;
    }

    /**
     * @param $driver
     * @return mixed
     * @throws Exception
     */
    public function driver($driver) {
        $driver_class = $this->_driver_class_prefix . $driver;

        if (class_exists($driver_class)) {
            $driver_object = new $driver_class;

            if (!$driver_object instanceof RCMS_Database_Driver)
                throw new Exception("Database error: driver {$driver} is not instance of main driver RCMS_Database_Driver");

            return $driver_object;
        } else
            throw new Exception("Database error: driver {$driver} is not available");
    }
}