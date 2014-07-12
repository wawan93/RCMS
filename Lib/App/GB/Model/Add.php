<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * GB Add Model
*/

class App_GB_Model_Add extends RCMS_Model {
	public function __construct($name, $email, $message) {
        parent::__construct();

        $name = Rev1lZ_Filters::filterHtmlTags($this->_db->safe($name));
        $email = Rev1lZ_Filters::filterHtmlTags($this->_db->safe($email));
        $message = Rev1lZ_Filters::filterHtmlTags($this->_db->safe($message));

        $messageLength = mb_strlen($message, "UTF-8");

        $interval = $this->_db
            ->select("count(*)")
            ->from("`" . DBPREFIX . "gb`")
            ->where("`author_ip`", "=", $this->_db->string(Rev1lZ_HTTP::getIp()))
            ->and_where("UNIX_TIMESTAMP(CURRENT_TIMESTAMP)", "<", "(UNIX_TIMESTAMP(`timestamp`) + " . $this->_config->get(1, "Add", "Interval") . ")")
            ->result_array();

        if ($interval === false) {
            $this->code = 1;
            $this->type = "danger";
            $this->message = $this->_lang->get(0, "Core", "internalError") . " (" . $this->_db->getError() . ")";
        } elseif (empty($name) || empty($email) || empty($message) || empty($capcha)) {
            $this->code = 2;
            $this->type = "warning";
            $this->message = $this->_lang->get(1, "Add", "emptyFields");
        } elseif ($interval[0][0] > 0) {
            $this->code = 1;
            $this->type = "danger";
            $this->message = $this->_lang->get(1, "Add", "smallInterval") . " (" . Rev1lZ_HTTP::getIp() . ")";
        } elseif (!Rev1lZ_Filters::isValidEmail($email)) {
            $this->code = 3;
            $this->type = "danger";
            $this->message = $this->_lang->get(1, "Add", "invalidEmail");
        } elseif ($messageLength < $this->_config->get(1, "Add", "MinLength")) {
            $this->code = 4;
            $this->type = "danger";
            $this->message = $this->_lang->get(1, "Add", "shortLength");
        } elseif ($messageLength > $this->_config->get(1, "Add", "MaxLength")) {
            $this->code = 5;
            $this->type = "danger";
            $this->message = $this->_lang->get(1, "Add", "longLength");
        } else {
            $query = $this->_db
                ->insert_into("`" . DBPREFIX . "gb`")
                ->values(array(
                    "`author`" => $this->_db->string($name),
                    "`author_ip`" => $this->_db->string(Rev1lZ_HTTP::getIp()),
                    "`email`" => $this->_db->string($email),
                    "`message`" => $this->_db->string($message)
                ))
                ->result();

            if ($query === false) {
                $this->code = 1;
                $this->type = "danger";
                $this->message = $this->_lang->get(0, "Core", "internalError") . " (" . $this->_db->getError() . ")";
            } else {
                $this->type = "success";
                $this->message = $this->_lang->get(1, "Add", "success");
            }
        }
	}
}