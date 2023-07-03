<?php
include_once("../http/json.php");

$jobs_password = getenv("JOBS_PASSWORD");

if (!isset($_GET["password"])) {
    JSON(array("message" => "Bad Request"), 400);
    die();
}

$get_password = $_GET["password"];


if ($get_password != $jobs_password) {
    JSON(array("message" => "Unauthorized"), 401);
    die();
}

?>