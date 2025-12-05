-- Player Table
-- For player account management
-- Based on OpenDarkEden database structure

CREATE TABLE IF NOT EXISTS Player (
    PlayerID VARCHAR(15) NOT NULL DEFAULT '',
    Password VARCHAR(16) NOT NULL DEFAULT '',
    Name VARCHAR(20) NOT NULL DEFAULT '',
    Sex ENUM('MALE','FEMALE') DEFAULT NULL,
    SSN VARCHAR(20) NOT NULL DEFAULT '',
    Telephone VARCHAR(15) DEFAULT NULL,
    Cellular VARCHAR(15) DEFAULT NULL,
    ZipCode VARCHAR(7) DEFAULT NULL,
    Address VARCHAR(100) DEFAULT NULL,
    Nation TINYINT(3) UNSIGNED DEFAULT NULL,
    Email VARCHAR(50) DEFAULT NULL,
    Homepage VARCHAR(50) DEFAULT NULL,
    Profile VARCHAR(255) DEFAULT NULL,
    Pub ENUM('PRIVATE','PUBLIC') NOT NULL DEFAULT 'PRIVATE',
    CurrentWorldID TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    CurrentServerGroupID TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    LogOn ENUM('LOGOFF','LOGON','GAME') NOT NULL DEFAULT 'LOGOFF',
    Access ENUM('ALLOW','DENY','WAIT','OUT') NOT NULL DEFAULT 'ALLOW',
    SpecialEventCount INT(10) UNSIGNED NOT NULL DEFAULT '0',
    Number INT(10) UNSIGNED NOT NULL DEFAULT '0',
    IYear INT(10) UNSIGNED NOT NULL DEFAULT '0',
    IMonth INT(10) UNSIGNED NOT NULL DEFAULT '0',
    IDay INT(10) UNSIGNED NOT NULL DEFAULT '0',
    Event VARCHAR(5) NOT NULL DEFAULT '',
    LoginIP VARCHAR(15) DEFAULT '255.255.255.255',
    LastSlot TINYINT(1) DEFAULT '0',
    creation_date DATE DEFAULT NULL,
    CurrentLoginServerID TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    PayType TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PayPlayDate DATETIME NOT NULL DEFAULT '2002-07-15 00:00:00',
    PayPlayHours INT(10) UNSIGNED NOT NULL DEFAULT '0',
    PayPlayFlag INT(10) UNSIGNED NOT NULL DEFAULT '0',
    LastLoginDate DATE NOT NULL DEFAULT '2002-07-01',
    LastLogoutDate DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    BillingUserKey INT(10) UNSIGNED NOT NULL DEFAULT '0',
    SuggestCID VARCHAR(30) DEFAULT '',
    Author TINYINT(1) NOT NULL DEFAULT '2',
    FamilyPayPlayDate DATETIME NOT NULL DEFAULT '2002-07-15 00:00:00',
    mailing ENUM('Y','N') DEFAULT 'Y',
    PRIMARY KEY (PlayerID),
    KEY IDX_SSN (SSN),
    KEY IDX_PayPlayDate (PayPlayDate),
    KEY IDX_Player (LogOn, CurrentLoginServerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Note: This table structure matches OpenDarkEden's Player table
-- The web panel uses these key fields:
--   - PlayerID: Player account ID (used for login)
--   - Password: Player password (should be hashed in production)
--   - SSN: Social Security Number (used for registration)
--   - LogOn: Login status (LOGOFF, LOGON, GAME)
--   - Access: Account access level (ALLOW, DENY, WAIT, OUT)
--   - Email: Player email address
--   - LoginIP: Last login IP address
--   - LastLoginDate: Last login date
--   - LastLogoutDate: Last logout date

-- Security Note:
-- ⚠️ The Password field is VARCHAR(16) which is too small for hashed passwords
-- For production, consider changing to VARCHAR(255) to support password_hash()
-- Example: ALTER TABLE Player MODIFY Password VARCHAR(255);

