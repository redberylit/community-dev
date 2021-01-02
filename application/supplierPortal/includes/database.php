<?php
require_once('medoo/Medoo.php');
//error_reporting(-1); // on
error_reporting(E_ALL);
ini_set('display_errors', 1);

$str = $_SERVER['DOCUMENT_ROOT'];
define("DIR_PATH", $str);

//require_once 'class/cls.db.php';
$database_sup = new database([
    // required
    'database_type' => 'mysql',
    'database_name' => 'srp_db',
    'server' => '192.168.1.5',
    'username' => 'mubashir',
    'password' => 'Subaru123',
    'charset' => 'utf8',
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

?>


