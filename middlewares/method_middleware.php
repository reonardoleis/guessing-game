<?php
include_once(dirname(__DIR__)."../http/json.php");
function method($methods) {
    $method = $_SERVER["REQUEST_METHOD"];
    if (!in_array($method, $methods)) {
        JSON(array("message" => "Method Not Allowed"), 405);
        die();
    }
}

?>