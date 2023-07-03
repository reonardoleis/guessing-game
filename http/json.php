<?php

function JSON($response, $status_code = 200) {
    header("Content-Type: application/json");
    http_response_code($status_code);
    if (!is_string($response)) {
        $response = json_encode($response);
    } 

    echo $response;
}

?>