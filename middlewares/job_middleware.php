<?php
include_once("../http/json.php");

$jobs_password = getenv("JOBS_PASSWORD");

$req = json_decode(file_get_contents("php://input"), true);
if (!isset($req) || !isset($req["password"])) {
    JSON(array("message" => "Bad Request"), 400);
    die();
}

if ($req["password"] != $jobs_password) {
    JSON(array("message" => "Unauthorized"), 401);
    die();
}

?>