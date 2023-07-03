<?php

function getenv_fallback($key, $fallback = "") {
    $value = getenv($key);
    if ($value === false) {
        return $fallback;
    }
    return $value;
}

?>