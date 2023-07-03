<?php
include_once("../middlewares/method_middleware.php");
method(array("GET"));
include_once("../middlewares/job_middleware.php");
include_once("../http/json.php");
include_once("../repositories/daily_word_repository.php");



$words = json_decode(file_get_contents("../assets/words.json"), true);

$word = $words[rand(0, count($words) - 1)];

if (create_daily_word($word)) {
    JSON(array("message" => "OK", "word" => $word));
    die();
}

JSON(array("message" => "Internal Server Error"), 500);
die();

?>