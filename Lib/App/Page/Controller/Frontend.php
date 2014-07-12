<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.o
 *
 * Page Frontend Controller
 */

class App_Page_Controller_Frontend extends RCMS_Controller {
    public $default_actions = false;

	public function Action_Index() {
        $name = $this->_router->get(1) ? $this->_router->get(1) : $this->_config->get(1, "Defaults", "Page");
		$page = new App_Page_Model_Show($name);

        $this->_core
            ->setTitle($page->title)
            ->addBreadcrumbs($page->title);

        if ($page->code == 0)
		    $this->_view
                ->add($page->viewName, $page->tags, $page->blocks)
                ->render($page->viewName, $page->render);
        else
            $this->_view
                ->alert($page->type, $page->message)
                ->render();
	}
}