# DarkEden Web Panel (DK2WEB) - Modernized Version

This is a modernized version of the DK2WEB community web panel for DarkEden servers.

## Features

- ✅ Player Rankings (Top Slayers, Vampires, Ousters)
- ✅ Server Statistics
- ✅ Player Management
- ✅ Item Shop
- ✅ Admin Panel
- ✅ Modern PHP 8.1+ compatible (uses MySQLi instead of deprecated mysql_*)
- ✅ Security improvements
- ✅ Responsive design

## Installation

### Step 1: Upload Files

Upload the `dk2web` folder to your web server:

```bash
# On your droplet
cd /var/www/denostaugia.com
# Upload dk2web folder here
```

Or use SCP:

```bash
# From your local machine
scp -r dk2web root@YOUR_DROPLET_IP:/var/www/denostaugia.com/
```

### Step 2: Configure Database

Edit `config.php`:

```php
$host = "localhost"; // or your MySQL host
$user = "elcastle"; // your database user
$pass = "elca110"; // your database password
$db = "DARKEDEN"; // your database name
```

### Step 3: Set Permissions

```bash
cd /var/www/denostaugia.com/dk2web
chown -R www-data:www-data .
chmod -R 755 .
```

### Step 4: Access the Panel

Visit: `http://denostaugia.com/dk2web/`

## Configuration Options

Edit `config.php` to customize:

- **Shop Settings:**
  - `$medalvalue = 150;` - Gold Medal value in shop points
  - `$jobprice = 200;` - Job Change price

- **Ranking Limits:**
  - `$slayer_rank_limit = 5;` - Number of top Slayers to show
  - `$vampire_rank_limit = 5;` - Number of top Vampires to show
  - `$ousters_rank_limit = 5;` - Number of top Ousters to show

## Security Notes

⚠️ **Important Security Recommendations:**

1. **Change default credentials** in `config.php`
2. **Protect admin pages** with authentication
3. **Use HTTPS** (SSL certificate)
4. **Restrict access** to admin functions
5. **Regular backups** of database

## Database Structure

The panel expects these tables:
- `Player` - Player accounts
- `PlayerCreature` - Character data
- `GuildInfo` - Guild information

Adjust queries in the PHP files if your table structure differs.

## Troubleshooting

### Database Connection Error

- Check MySQL is running: `systemctl status mysql`
- Verify credentials in `config.php`
- Test connection: `mysql -u elcastle -pelca110 DARKEDEN`

### Page Not Found

- Check file permissions
- Verify nginx is configured correctly
- Check error logs: `tail -f /var/log/nginx/denostaugia.com.error.log`

### Rankings Not Showing

- Verify table names match your database
- Check column names (Level, Fame, Race, Name)
- Adjust queries in `rankings.php` to match your schema

## Original Credits

- Original DK2WEB by: PeChU
- Enhanced by: BloodyShade
- Modernized for: PHP 8.1+ and OpenDarkEden

## Next Steps

1. Add authentication system
2. Implement shop functionality
3. Add player search
4. Create admin panel features
5. Add guild management

## Support

For issues or questions:
- Check database connection
- Review nginx error logs
- Verify table structure matches queries
- Test database queries directly in MySQL

Quick setup
Upload all files to your server
Create database tables:
   mysql -u elcastle -pelca110 DARKEDEN < sql/goldmedal.sql   mysql -u elcastle -pelca110 DARKEDEN < sql/GoodsListInfo.sql
Access: http://denostaugia.com/dk2web/