<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS View Main class
 */

class RCMS_View_Main {
    protected static $_instance;
    protected $_driver_class_prefix = "RCMS_View_Driver_";

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

            if (!$driver_object instanceof RCMS_View_Driver)
                throw new Exception("View error: driver {$driver} is not instance of main driver RCMS_View_Driver");

            return $driver_object;
        } else
            throw new Exception("View error: driver {$driver} is not available");
    }
}