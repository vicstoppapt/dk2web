-- Goods List Info Table
-- For item shop

CREATE TABLE IF NOT EXISTS GoodsListInfo (
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    ItemName VARCHAR(255) NOT NULL,
    ItemCode INT NOT NULL,
    Price INT NOT NULL DEFAULT 0,
    Description TEXT,
    Available TINYINT(1) DEFAULT 1,
    INDEX idx_itemcode (ItemCode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Example items (adjust based on your item codes)
INSERT INTO GoodsListInfo (ItemName, ItemCode, Price, Description) VALUES
('Health Potion', 1001, 100, 'Restores HP'),
('Mana Potion', 1002, 100, 'Restores MP'),
('Gold Medal', 2001, 150, 'Shop currency');

