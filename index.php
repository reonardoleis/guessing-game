<?php
require_once("middlewares/method_middleware.php");
method(array("GET"));
require_once("repositories/daily_word_repository.php");
require_once("env_utils/env_utils.php");
date_default_timezone_set("UTC");

?>



<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Guessing Game</title>
    <link rel="stylesheet" href="css/style.css?r=<?php echo rand(0, 2 ** 16); ?>" />
    <script>
        let nextWordTimestamp = <?php
                                $tomorrow_midnight_utc = strtotime("tomorrow");
                                // convert to JS style timestamp
                                $tomorrow_midnight_utc *= 1000;
                                echo $tomorrow_midnight_utc;
                                ?>;

    </script>
</head>

<body class="background-primary">
    <div class="game">
        <h1 class="text-light">Guessing Game</h1>
        <?php

        $attemps = getenv_fallback("ATTEMPTS", 6);

        for ($i = 0; $i < $attemps; $i++) {
            echo "<div class='input-row'>";
            for ($j = 1; $j <= 5; $j++) {
                if ($i == 0) {
                    echo "<input class='input-square active' type='text' id='letter-$i-$j' class='letter' maxlength='1' />";
                } else {
                    echo "<input class='input-square' type='text' id='letter-$i-$j' class='letter' maxlength='1' disabled/>";
                }
            }
            echo "</div>";
        }

        ?>

        <button class="attempt-btn" onclick="attempt()">Attempt</button>
    </div>
    <div class="container hidden" id="win">
        Your guess was correct!
    </div>
    <div class="container hidden" id="lose">
        Your guess was incorrect and you have no more attemps.
    </div>
    <div class="container" id="next-word">

    </div>
    <script src="js/main.js?r=<?php rand(0, 2**16); ?>"></script>
</body>

</html>