# DK2WEB Installation Guide

## Complete File List

Based on the original DK2WEB from [dssz.com](https://www.dssz.com/1311596.html), this modernized version includes:

### Core Files
- ✅ `config.php` - Database configuration
- ✅ `index.php` - Main dashboard
- ✅ `login.php` - Player login
- ✅ `home.php` - Player home page
- ✅ `logout.php` - Logout handler
- ✅ `ranking.php` / `rankings.php` - Player rankings
- ✅ `medal.php` - Gold medal management
- ✅ `downloads.php` - Client downloads
- ✅ `delete_char.php` - Character deletion
- ✅ `style.css` - Stylesheet

### Registration
- ✅ `reg/reg.php` - Player registration
- ✅ `reg/idreg.php` - (Can be same as reg.php)

### Shop
- ✅ `shop/index.php` - Shop main page
- ✅ `shop/buy.php` - Purchase items
- ✅ `shop/char.php` - Character selection for shop

### Job Change
- ✅ `jobchange/index.php` - Job change main page
- ✅ `jobchange/buy.php` - (Can redirect to char.php)
- ✅ `jobchange/char.php` - Character job change

### Database SQL
- ✅ `sql/goldmedal.sql` - Gold medal table
- ✅ `sql/GoodsListInfo.sql` - Shop items table

## Installation Steps

### 1. Upload Files

```bash
# Upload entire dk2web folder to your server
scp -r website/dk2web root@YOUR_DROPLET_IP:/var/www/denostaugia.com/
```

### 2. Set Permissions

```bash
cd /var/www/denostaugia.com/dk2web
chown -R www-data:www-data .
chmod -R 755 .
```

### 3. Create Database Tables

```bash
# On your droplet
mysql -u elcastle -pelca110 DARKEDEN < /var/www/denostaugia.com/dk2web/sql/goldmedal.sql
mysql -u elcastle -pelca110 DARKEDEN < /var/www/denostaugia.com/dk2web/sql/GoodsListInfo.sql
```

### 4. Configure Database

Edit `config.php`:
- Database host, user, password
- Server ports
- Shop prices
- Ranking limits

### 5. Create Downloads Folder

```bash
mkdir -p /var/www/denostaugia.com/dk2web/down
chown www-data:www-data /var/www/denostaugia.com/dk2web/down
# Upload client files here
```

### 6. Access the Panel

Visit: `http://denostaugia.com/dk2web/`

## Missing Files (Optional)

These were in the original but may not be needed:

- `convert.php` - Character conversion (if needed)
- `delete.php` - General delete handler
- `learn.php` - Skill learning (if needed)
- `log.php` - Log viewer
- `reflection.php` - Character reflection/viewer

You can create these later if needed.

## Security Notes

⚠️ **IMPORTANT:**

1. **Change default passwords** in `config.php`
2. **Implement password hashing** (currently uses plaintext)
3. **Add admin authentication** for admin.php
4. **Use HTTPS** (SSL certificate)
5. **Validate all inputs** (some validation exists, but review)
6. **Protect sensitive operations** with additional checks

## Database Adjustments

You may need to adjust table/column names based on your OpenDarkEden database structure:

- `Player` table structure
- `PlayerCreature` table structure  
- `GoldMedal` table (create if doesn't exist)
- `GoodsListInfo` table (create if doesn't exist)
- `GuildInfo` table structure

Check your database schema and adjust queries accordingly.

## Testing

1. Test registration: `http://denostaugia.com/dk2web/reg/reg.php`
2. Test login: `http://denostaugia.com/dk2web/login.php`
3. Test rankings: `http://denostaugia.com/dk2web/rankings.php`
4. Test shop: `http://denostaugia.com/dk2web/shop/`

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify credentials in config.php
- Test: `mysql -u elcastle -pelca110 DARKEDEN`

### Tables Not Found
- Run SQL files to create tables
- Check table names match your database

### Login Not Working
- Verify Player table exists
- Check password column name
- Review login.php queries

### Shop Not Working
- Create GoodsListInfo table
- Add items to GoodsListInfo
- Verify GoldMedal table exists

## Next Steps

1. ✅ Install and test basic functionality
2. ✅ Add items to GoodsListInfo table
3. ✅ Implement password hashing
4. ✅ Add admin authentication
5. ✅ Customize styling
6. ✅ Add more features as needed


Quick setup
Upload all files to your server
Create database tables:
   mysql -u elcastle -pelca110 DARKEDEN < sql/goldmedal.sql   mysql -u elcastle -pelca110 DARKEDEN < sql/GoodsListInfo.sql
Access: http://denostaugia.com/dk2web/