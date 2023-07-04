CREATE TABLE tip_history(
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(30) NOT NULL,
    daily_word_id BIGINT NOT NULL,
    tip_idx INT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (daily_word_id) REFERENCES daily_words(id) 
    ON UPDATE CASCADE ON DELETE CASCADE
);