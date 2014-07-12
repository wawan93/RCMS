<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Bootstrap
 */

try {
    function __autoload ($className) {
        $classFile = LIB . DS . str_replace("_", DS, $className) . EXT;

        if (file_exists($classFile)) {
            require_once $classFile;

            if (!class_exists($className))
                die("Class {$className} not exist in file {$classFile}.");

            return true;
        } else
            return false;
    }

    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
        define("AJAX", true);
    }

    $registry = RCMS_Registry::getInstance();

    $config = RCMS_Registry::getInstance()
        ->addObject("Config", new RCMS_Config)
        ->addObject("Lang", new RCMS_Lang)
        ->getObject("Config");

    define("SITE_PATH", $config->get(0, "Site", "Path") . ($config->get(0, "Core", "RewriteRoutes") ? "" : "index.php?go="));
    define("DBPREFIX", $config->get(0, "Database", "Prefix"));

    $registry
        ->addObject("Core", new RCMS_Core)
        ->addObject("Database", RCMS_Database_Main::getInstance()->driver($config->get(0, "Database", "Driver")))
        ->addObject("Router", new RCMS_Router)
        ->addObject("User", new RCMS_User)
        ->addObject("View", RCMS_View_Main::getInstance()->driver($config->get(0, "View", "Driver")))
        ->getObject("Database")
        ->connect(
            $config->get(0, "Database", "Host"),
            $config->get(0, "Database", "User"),
            $config->get(0, "Database", "Password"),
            $config->get(0, "Database", "Base")
        );

    $registry
        ->getObject("Core")
        ->addMetaArray(array (
            "charset" => $config->get(0, "Site", "Charset"),
            "description" => $config->get(0, "Site", "Description"),
            "keywords" => $config->get(0, "Site", "Keywords")
        ))
        ->addCssArray(array (
            "rcms/css/core.css"
        ))
        ->addJsArray(array (
            "rcms/js/jquery.min.js",
            "rcms/js/core.js"
        ))
        ->addBreadcrumbs($config->get(0, "Site", "Name"), "");

    $registry
        ->getObject("Router")
        ->start();
} catch(Exception $e) {
    echo "<h1>RCMS Error</h1>\n";

    echo $e->getMessage() . "<pre>" . $e->getTraceAsString() . "</pre>";
}