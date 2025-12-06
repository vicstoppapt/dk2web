-- Update Server IP in Database
-- Run this after connecting to MySQL

USE DARKEDEN;

-- Update GameServerInfo table with your server IP
UPDATE GameServerInfo SET IP = '167.71.106.232';

-- Update WorldDBInfo table - Change Host from 'odk-mysql' to 'darkeden-mysql'
-- Note: WorldDBInfo uses 'Host' column, not 'IP'
UPDATE WorldDBInfo SET Host = 'darkeden-mysql';

-- Verify the changes
SELECT 'GameServerInfo:' AS TableName;
SELECT * FROM GameServerInfo;

SELECT 'WorldDBInfo:' AS TableName;
SELECT * FROM WorldDBInfo;

