<?php
require_once(dirname(__DIR__)."/database/database.php");

function create_daily_word($word) {
    $now = date("Y-m-d H:i:s");
    $word_content = $word["word"];
    $word_definition = $word["definition"];
    $sql = "INSERT INTO daily_words (word, word_definition, created_at, updated_at) VALUES ('$word_content', '$word_definition', '$now', '$now')";
    global $conn;
    $conn->query($sql);
    if ($conn->error) {
        return false;
    }

    return true;
}

function find_current_word() {
    $sql = "SELECT * FROM daily_words ORDER BY created_at DESC LIMIT 1";
    global $conn;
    $result = $conn->query($sql);
    if ($conn->error) {
        return null;
    }

    if ($result->num_rows == 0) {
        return null;
    }

    $current_word = $result->fetch_assoc();
    return $current_word["word"];
}

function find_current_word_full() {
    $sql = "SELECT * FROM daily_words ORDER BY created_at DESC LIMIT 1";
    global $conn;
    $result = $conn->query($sql);
    if ($conn->error) {
        return null;
    }

    if ($result->num_rows == 0) {
        return null;
    }

    $current_word = $result->fetch_assoc();
    return $current_word;
}

?>