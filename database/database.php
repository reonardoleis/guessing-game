<?php
require_once(dirname(__DIR__)."../env_utils/env_utils.php");

$conn = new mysqli(getenv_fallback("DB_HOST", "localhost"), getenv_fallback("DB_USERNAME", "root"), getenv_fallback("DB_PASSWORD", ""), getenv_fallback("DB_NAME", "guessing_game"));
?>