CREATE DATABASE IF NOT EXISTS DARKEDEN;
CREATE DATABASE IF NOT EXISTS USERINFO;

-- SECURITY: Changed from default 'elcastle'/'elca110' credentials
-- These default credentials are publicly known and pose a security risk
-- Always use unique, strong credentials for your server!
CREATE USER 'denostaugia'@'%' IDENTIFIED BY 'denostaugia2024';
GRANT ALL PRIVILEGES ON DARKEDEN.* TO 'denostaugia'@'%';
GRANT ALL PRIVILEGES ON USERINFO.* TO 'denostaugia'@'%';
FLUSH PRIVILEGES;