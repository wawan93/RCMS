<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * Translit Show Model
 */

class App_Translit_Model_Show extends RCMS_Model {
	public function __construct($text) {
        parent::__construct();

        $this->title = $this->lang->get(1, "Main", "moduleName");
        $this->viewName = "Translit/Page";
        $this->tags = array (
            "text" => $text,
            "translit" => Rev1lZ_Converters::toTranslit($text)
        );
	}
}