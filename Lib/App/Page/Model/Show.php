<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * Page Show Model
 */

class App_Page_Model_Show extends RCMS_Model {
	public function __construct($name) {
        parent::__construct();

        $array = $this->db
            ->select(array (
                "`name`", "`text`", "`auto_warp`"
            ))
            ->from("`" . DBPREFIX . "pages`")
            ->where("url", "=", $this->db->string($name))
            ->result_array();

        if ($array === false) {
            $this->code = 1;
            $this->title = $this->lang->get(0, "Core", "internalError");
            $this->type = "danger";
            $this->message = $this->lang->get(0, "Core", "internalError") . " (" . $this->db->getError() . ")";
        } elseif (count($array) > 0) {
            $this->title = $array[0]["name"];
            $this->render = null;
            $this->viewName = "Page/Page";
            $this->tags =  array (
                "title" => $array[0]["name"],
                "content" => ($array[0]["auto_warp"] == 1) ? nl2br($array[0]["text"]) : $array[0]["text"]
            );
        } else {
            $this->code = 2;
            $this->title = $this->lang->get(1, "Show", "notFound");
            $this->type = "danger";
            $this->message = $this->lang->get(1, "Show", "notFound") . " (" . $name . ")";
        }
	}
}