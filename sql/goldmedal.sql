-- Gold Medal Table
-- For tracking player shop points

CREATE TABLE IF NOT EXISTS GoldMedal (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    PlayerID VARCHAR(50) NOT NULL,
    Amount INT NOT NULL DEFAULT 0,
    Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_playerid (PlayerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

