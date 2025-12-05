# View Mode - View Pages Without Database

## Overview

The DK2WEB panel now supports **VIEW MODE** which allows you to view all pages without a database connection. This is perfect for:
- Previewing the website design
- Testing the UI/UX
- Showing the panel to others before setup
- Development without database

## How It Works

### View Mode is Enabled by Default

In `config.php`, there's a constant:
```php
define('VIEW_MODE', true); // Set to false when database is ready
```

When `VIEW_MODE` is `true`:
- ✅ No database connection is attempted
- ✅ All SQL queries are skipped
- ✅ Mock data is displayed instead
- ✅ Pages load without errors

## Mock Data Provided

### Home Page (index.php)
- Total Players: 1,250
- Online Players: 42
- Total Characters: 3,420
- Guilds: 18

### Rankings (rankings.php)
- Top 5 Slayers with sample data
- Top 5 Vampires with sample data
- Top 5 Ousters with sample data

### Other Pages
- Players search: Shows sample results
- Shop: Shows "No items available" message
- Medal: Shows sample medal count

## Enabling Database Mode

When you're ready to connect to a real database:

1. **Edit `config.php`**:
   ```php
   define('VIEW_MODE', false); // Enable database
   ```

2. **Configure database credentials**:
   ```php
   $host = "localhost";
   $user = "elcastle";
   $pass = "elca110";
   $db = "DARKEDEN";
   ```

3. **Make sure database is accessible**

4. **Run SQL files** to create tables:
   ```bash
   mysql -u elcastle -pelca110 DARKEDEN < sql/Player.sql
   mysql -u elcastle -pelca110 DARKEDEN < sql/goldmedal.sql
   mysql -u elcastle -pelca110 DARKEDEN < sql/GoodsListInfo.sql
   ```

## Error Handling

All database queries are wrapped in try-catch blocks:
- If database connection fails → Uses mock data
- If SQL query fails → Shows empty state or mock data
- No 500 errors → Pages always load

## Files Modified

The following files now support VIEW MODE:
- ✅ `config.php` - View mode flag and graceful connection
- ✅ `index.php` - Mock statistics
- ✅ `rankings.php` - Mock ranking data
- ✅ `players.php` - Mock search results
- ✅ `shop/index.php` - Graceful error handling
- ✅ `medal.php` - Mock medal data
- ✅ `home.php` - Graceful error handling

## Testing

1. **View Mode (Current)**:
   - Visit any page
   - Should load without errors
   - Shows mock data

2. **Database Mode (After Setup)**:
   - Set `VIEW_MODE = false`
   - Configure database
   - Real data will be displayed

## Notes

- **Login/Registration**: Still requires database (these pages redirect or show errors in view mode)
- **Admin Panel**: Works in view mode (shows warning message)
- **Shop/Medal Pages**: Require login, but won't crash if database unavailable

## Troubleshooting

### Still Getting 500 Errors?

1. Check PHP error logs:
   ```bash
   tail -f /var/log/nginx/denostaugia.com.error.log
   ```

2. Make sure `VIEW_MODE` is set to `true` in `config.php`

3. Check file permissions:
   ```bash
   chmod 644 config.php
   ```

4. Verify PHP syntax:
   ```bash
   php -l config.php
   ```

## Next Steps

1. ✅ View pages in VIEW MODE (current state)
2. ⏳ Set up database
3. ⏳ Run SQL files
4. ⏳ Set `VIEW_MODE = false`
5. ⏳ Test with real data

