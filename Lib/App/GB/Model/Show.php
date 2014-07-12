<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * GB Show Model
 */

class App_GB_Model_Show extends RCMS_Model {
    public $view = "GB/Page";

	public function __construct($page) {
        parent::__construct();

        $this->title = $this->_lang->get(1, "Main", "moduleName");

        $num = $this->_db
            ->select(array(
                "count(*)"
            ))
            ->from("`" . DBPREFIX . "gb`")
            ->result_array();

        if ($num === false) {
            $this->code = 1;
            $this->type = "danger";
            $this->message = $this->_lang->get(0, "Core", "internalError") . " (" . $this->_db->getError() . ")";

            return;
        } else {
            $num = $num[0][0];
            $pagination = new Rev1lZ_Pagination($num, $page, SITE_PATH . "GB/Page/", $this->_config->get(1, "Defaults", "customPagination"));

            $array = $this->_db
                ->select(array(
                    "`id`", "`author`", "`email`", "`message`", "UNIX_TIMESTAMP(`timestamp`) as `timestamp`"
                ))
                ->from("`" . DBPREFIX . "gb`")
                ->order_by("`id` " . $this->_db->safe($this->_config->get(1, "Defaults", "Sort")))
                ->limit($pagination->getSqlLimits())
                ->result_array();

            if ($array === false) {
                $this->code = 1;
                $this->type = "danger";
                $this->message = $this->_lang->get(0, "Core", "internalError") . " (" . $this->_db->getError() . ")";
            } else {
                foreach ($array as $row) {
                    $this->_view->add("GB/Post", array(
                        "id" => $row["id"],
                        "author" => $row["author"],
                        "email" => $row["email"],
                        "message" => nl2br($row["message"]),
                        "date" => $this->_core->getDate($row["timestamp"]),
                        "time" => $this->_core->getTime($row["timestamp"]),
                    ));
                }

                $this->_view->add("GB/AddForm");

                $this->code = 0;
                $this->viewName = "GB/Page";
                $this->tags = array (
                    "num" => $num,
                    "posts" => $this->_view->get("GB/Post") . $pagination,
                    "add-form" => $this->_view->get("GB/AddForm")
                );
            }
        }
	}
}