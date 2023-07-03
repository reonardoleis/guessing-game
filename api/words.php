<?php
include_once(dirname(__DIR__)."../middlewares/method_middleware.php");
include_once(dirname(__DIR__)."../repositories/daily_word_repository.php");
include_once(dirname(__DIR__)."../http/json.php");

method(array("POST", "GET"));

$method = $_SERVER["REQUEST_METHOD"];

function guess_word() {
    $req = json_decode(file_get_contents("php://input"), true);
    if (!isset($req["word"])) {
        JSON(array("message" => "Bad Request"), 400);
        die();
    }

    $word = $req["word"];
    $word = strtolower($word);
    
    $current_word = find_current_word();

    $word_seq = str_split($word, 1);
    $current_word_seq = str_split($current_word, 1);

    $correct_letters = [false, false, false, false, false];

    for ($i = 0; $i < count($word_seq); $i++) {
        if ($word_seq[$i] == $current_word_seq[$i]) {
            $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => true, "exists" => true);
        } else {
            $occurrences_in_current_word = array_keys($current_word_seq, $word_seq[$i]);
            $occurrences_in_correct_letters = array_keys($correct_letters, array("letter" => $word_seq[$i], "position" => true, "exists" => true));
            $occurrences_in_correct_letters += array_keys($correct_letters, array("letter" => $word_seq[$i], "position" => false, "exists" => true));
            if ($occurrences_in_correct_letters >= $occurrences_in_current_word) {
                $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => false, "exists" => false);
                continue;
            }
            $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => false, "exists" => in_array($word_seq[$i], $current_word_seq));
        }
    }

    echo JSON(array("word" => $current_word, "is_correct" => $word == $current_word, "correct_letters" => $correct_letters) , 200);

    die();
}

function get_current_word_id() {
    $current_word_id = find_current_word_id();
    if ($current_word_id == null) {
        JSON(array("message" => "Internal Server Error"), 500);
        die();
    }

    JSON(array("id" => $current_word_id), 200);
    die();
}

if ($method == "POST") {
    return guess_word();
} else if ($method == "GET") {
    return get_current_word_id();
}

?>