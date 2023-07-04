<?php
include_once(dirname(__DIR__)."/middlewares/method_middleware.php");
include_once(dirname(__DIR__)."/repositories/tip_history_repository.php");
include_once(dirname(__DIR__)."/repositories/daily_word_repository.php");
include_once(dirname(__DIR__)."/http/json.php");

method(array("GET"));

$method = $_SERVER["REQUEST_METHOD"];

function get_tip() {
    $requester_ip = $_SERVER["REMOTE_ADDR"];
    
    $tip_count = get_tip_count($requester_ip);
    if ($tip_count >= 2) {
        JSON(array("message" => "You have reached the limit of tips for today."), 400);
        die();
    }

    $current_word = find_current_word_full();
    $current_word_chars = str_split($current_word["word"]);
    $current_words_seq = array();
    for ($i = 0; $i < count($current_word_chars); $i++) {
        $current_words_seq[] = array("char" => $current_word_chars[$i], "index" => $i);
    }

    $tipped_chars = get_tipped_chars($requester_ip, $current_word["id"]);

   

    for ($i = 0; $i < count($tipped_chars); $i++) {
        unset($current_words_seq[$tipped_chars[$i]]);
    }


    $random_char = $current_words_seq[array_rand($current_words_seq)];
    $random_char_idx = $current_words_seq[array_rand($current_words_seq)]["index"];

    if (!create_tip_history($requester_ip, $random_char_idx, $current_word["id"])) {
        JSON(array("message" => "Something went wrong."), 500);
        die();
    }

    JSON(array("char" => $random_char["char"], "index" => $random_char["index"]));
    die();
}

if ($method == "GET") {
    get_tip();
}

?>