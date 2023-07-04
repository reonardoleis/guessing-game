CREATE TABLE daily_words(
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(20) NOT NULL,
    word_definition TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
)