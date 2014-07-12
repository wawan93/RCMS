<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS View Main class
 */

abstract class RCMS_View_Driver {
    /**
     * @param $name
     * @param array $tags
     * @param array $blocks
     */
    public abstract function add($name, $tags = array(), $blocks = array());

    /**
     * @param $type
     * @param $message
     */
    public abstract function alert($type, $message);

    /**
     * @param $stack
     */
    public abstract function get($stack);

    /**
     * @param null $stack
     * @param null $mainView
     */
    public abstract function render($stack = null, $mainView = null);

    /**
     * @param $object
     */
    public function jsonRender($object) {
        echo json_encode($object);
        exit;
    }
}