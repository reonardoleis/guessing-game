# guessing-game
Inspired by term.ooo game. Experimenting with pure PHP, HTML, CSS and JavaScript. No frameworks. No libraries.

# how to run
All you need to do is set your database connection information on `database/config.php` file. You should follow `database/config.php.example` file.

To reset the word, you should make a GET request to `BASE_URL/jobs/word_selection_job?password=XYZ` where XYZ is the `JOBS_PASSWORD` environment variable. You can set a cronjob to make it automatic.
