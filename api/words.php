<?php
include_once(dirname(__DIR__)."/middlewares/method_middleware.php");
include_once(dirname(__DIR__)."/repositories/daily_word_repository.php");
include_once(dirname(__DIR__)."/http/json.php");

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
    $logs = [];
    for ($i = 0; $i < count($word_seq); $i++) {
        if ($word_seq[$i] == $current_word_seq[$i]) {
            $current_word_letter_count = count(array_keys($current_word_seq, $word_seq[$i]));
            $correct_letters_letter_count = 0;
            for ($j = 0; $j < $i; $j++) {
                if ($correct_letters[$j] == false) continue;
                if ($correct_letters[$j]["letter"] == $word_seq[$i] && ($correct_letters[$j]["exists"] == true || $correct_letters[$j]["position"] == true)) {
                    $correct_letters_letter_count++;
                }
            }

            if ($correct_letters_letter_count >= $current_word_letter_count) {
                for ($j = 0; $j < $i; $j++) {
                    if ($correct_letters[$j] == false) continue;
                    if ($correct_letters[$j]["letter"] == $word_seq[$i] && $correct_letters[$j]["exists"] == true && $correct_letters[$j]["position"] == false) {
                        $correct_letters[$j]["exists"] = false;
                        break;
                    }
                }
            }
            $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => true, "exists" => true);
        } else {
            $current_word_letter_count = count(array_keys($current_word_seq, $word_seq[$i]));
            $correct_letters_letter_count = 0;
            for ($j = 0; $j < $i; $j++) {
                if ($correct_letters[$j] == false) continue;
                if ($correct_letters[$j]["letter"] == $word_seq[$i] && ($correct_letters[$j]["exists"] == true || $correct_letters[$j]["position"] == true)) {
                    $correct_letters_letter_count++;
                }
            }

            if ($correct_letters_letter_count >= $current_word_letter_count) {
                $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => false, "exists" => false);
                continue;
            }

            $correct_letters[$i] = array("letter" => $word_seq[$i], "position" => false, "exists" => in_array($word_seq[$i], $current_word_seq));
        }
    }

    for ($i = 0; $i < count($correct_letters); $i++) {
       unset($correct_letters[$i]["letter"]);
    }

    echo JSON(array("is_correct" => $word == $current_word, "correct_letters" => $correct_letters, "logs" => $logs) , 200);

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