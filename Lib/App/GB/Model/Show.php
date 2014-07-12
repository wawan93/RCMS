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

        $this->title = $this->lang->get(1, "Main", "moduleName");

        $num = $this->db
            ->select(array(
                "count(*)"
            ))
            ->from("`" . DBPREFIX . "gb`")
            ->result_array();

        if ($num === false) {
            $this->code = 1;
            $this->type = "danger";
            $this->message = $this->lang->get(0, "Core", "internalError") . " (" . $this->db->getError() . ")";

            return;
        } else {
            $num = $num[0][0];
            $pagination = new Rev1lZ_Pagination($num, $page, SITE_PATH . "GB/Page/", $this->config->get(1, "Defaults", "customPagination"));

            $array = $this->db
                ->select(array(
                    "`id`", "`author`", "`email`", "`message`", "UNIX_TIMESTAMP(`timestamp`) as `timestamp`"
                ))
                ->from("`" . DBPREFIX . "gb`")
                ->order_by("`id` " . $this->db->safe($this->config->get(1, "Defaults", "Sort")))
                ->limit($pagination->getSqlLimits())
                ->result_array();

            if ($array === false) {
                $this->code = 1;
                $this->type = "danger";
                $this->message = $this->lang->get(0, "Core", "internalError") . " (" . $this->db->getError() . ")";
            } else {
                foreach ($array as $row) {
                    $this->view->add("GB/Post", array(
                        "id" => $row["id"],
                        "author" => $row["author"],
                        "email" => $row["email"],
                        "message" => nl2br($row["message"]),
                        "date" => $this->core->getDate($row["timestamp"]),
                        "time" => $this->core->getTime($row["timestamp"]),
                    ));
                }

                $this->view->add("GB/AddForm");

                $this->code = 0;
                $this->viewName = "GB/Page";
                $this->tags = array (
                    "num" => $num,
                    "posts" => $this->view->get("GB/Post") . $pagination,
                    "add-form" => $this->view->get("GB/AddForm")
                );
            }
        }
	}
}