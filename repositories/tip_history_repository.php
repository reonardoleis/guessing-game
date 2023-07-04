<?php
require_once(dirname(__DIR__)."/database/database.php");
require_once(dirname(__DIR__)."/repositories/daily_word_repository.php");

function get_tip_count($ip) {
    $current_word = find_current_word_full();
    $current_word_id = $current_word["id"];

    $sql = "SELECT COUNT(id) as count FROM tip_history WHERE ip = '$ip' AND daily_word_id = $current_word_id";
    global $conn;
    $result = $conn->query($sql);
    if ($conn->error) {
        return false;
    }

    $count = $result->fetch_assoc()["count"];
    return $count;
}

function get_tipped_chars($ip, $current_word_id) {
    global $conn; 

    $tipped_chars = array();
    $sql = "SELECT * FROM tip_history WHERE ip = '$ip' AND daily_word_id = $current_word_id";
    $result = $conn->query($sql);
    if ($conn->error) {
        return false;
    }

    while ($row = $result->fetch_assoc()) {
        $tipped_chars[] = $row["tip_idx"];
    }

    return $tipped_chars;
}

function create_tip_history($ip, $random_char_idx, $current_word_id) {
    global $conn;

    $now = date("Y-m-d H:i:s");
    $sql = "INSERT INTO tip_history (ip, daily_word_id, tip_idx, created_at, updated_at) VALUES ('$ip', $current_word_id, '$random_char_idx','$now', '$now')";
    $conn->query($sql);
    if ($conn->error) {
        return false;
    }

    return true;
}

?>