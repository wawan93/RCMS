<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Index
 */

@ini_set("display_errors", 1);
@error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

$start_array = explode(" ", microtime());
$start_time = $start_array[1] + $start_array[0];

define ("DS",  DIRECTORY_SEPARATOR);
define ("EXT", ".php");

define ("PUB",  __DIR__);
define ("APP",  dirname(PUB));

define ("LIB",  APP . DS . "Lib");
define ("DATA",  APP . DS . "Data");
define ("VIEW",  APP . DS . "View");

require_once APP . "/Bootstrap.php";

$end_array = explode(" ", microtime());
$end_time = $end_array[1] + $end_array[0];
$time = intval(($end_time - $start_time) * 1000);

echo "\n<!-- GEN TIME: " . $time . " ms -->";