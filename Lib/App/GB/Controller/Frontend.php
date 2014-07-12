<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * GB Frontend Controller
 */

class App_GB_Controller_Frontend extends RCMS_Controller {
    private function Add() {
        if (isset($_POST["name"], $_POST["email"], $_POST["message"])) {
            $add = new App_GB_Model_Add($_POST["name"], $_POST["email"], $_POST["message"]);

            $this->_view
                ->alert($add->type, $add->message);
        }
    }
    private function Show($page) {
        $show = new App_GB_Model_Show($page);

        $this->_core
            ->addBreadcrumbs($show->title)
            ->setTitle($show->title)
            ->addJs("app/gb.js");

        if ($show->code == 0)
            $this->_view
                ->add($show->viewName, $show->tags, $show->blocks)
                ->render($show->viewName, $show->render);
        else
            $this->_view
                ->alert($show->type, $show->message)
                ->render();
    }
	public function Action_Index() {
        if (defined("AJAX") && isset($_POST["name"], $_POST["email"], $_POST["message"])) {
            $add = new App_GB_Model_Add($_POST["name"], $_POST["email"], $_POST["message"]);
            $show = new App_GB_Model_Show(1);

            $this->_view->jsonRender((object) array(
                "add" => $add,
                "posts" => (object) array (
                    "num" => $show->tags["num"],
                    "rows" => $show->tags["posts"]
                )
            ));
        }

        $this->add();
        $this->show(1);
	}

    public function Action_Page() {
        $this->add();

        $page = ($this->_router->get(2) !== false) ? $this->_router->get(2) : 1;
        $this->show($page);
    }
}