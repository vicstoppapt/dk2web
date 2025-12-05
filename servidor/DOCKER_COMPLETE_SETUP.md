# Complete Docker Setup Guide for OpenDarkEden Server

This guide provides a complete Docker-based setup for:
- OpenDarkEden Game Server (MySQL + Login/Shared/Game servers)
- Nginx + PHP-FPM for the web panel (DK2WEB)
- All services running in Docker containers

## Prerequisites

- Digital Ocean droplet with Docker and Docker Compose installed
- At least 2GB RAM (4GB+ recommended)
- Domain name pointing to your droplet IP (optional but recommended)

## Quick Start

1. **Clone or upload the server files to your droplet:**
   ```bash
   cd /opt
   git clone <your-repo> opendarkeden
   # OR upload files via SCP/SFTP
   ```

2. **Navigate to the server directory:**
   ```bash
   cd /opt/opendarkeden/server
   ```

3. **Build and start all services:**
   ```bash
   docker-compose -f docker/docker-compose.yml up -d --build
   ```

4. **Initialize the database:**
   ```bash
   # Wait for MySQL to be ready (about 30 seconds)
   sleep 30
   
   # Run the setup script
   docker exec -it darkeden-mysql mysql -u root -p123456 < /docker-entrypoint-initdb.d/a-setup.sql
   ```

5. **Load database schemas:**
   ```bash
   docker exec -i darkeden-mysql mysql -u denostaugia -p'denostaugia2024' DARKEDEN < initdb/DARKEDEN.sql
   docker exec -i darkeden-mysql mysql -u denostaugia -p'denostaugia2024' USERINFO < initdb/USERINFO.sql
   ```

6. **Update server IP in database:**
   ```bash
   # Replace YOUR_DROPLET_IP with your actual IP
   docker exec -it darkeden-mysql mysql -u denostaugia -p'denostaugia2024' DARKEDEN -e "UPDATE GameServerInfo SET IP = 'YOUR_DROPLET_IP'; UPDATE WorldDBInfo SET IP = 'YOUR_DROPLET_IP';"
   ```

7. **Start the game servers:**
   ```bash
   docker exec -d darkeden-server /home/darkeden/vs/bin/start.sh
   ```

8. **Access your web panel:**
   - Open `http://YOUR_DROPLET_IP` in your browser
   - Or `http://yourdomain.com` if you configured DNS

## Complete Docker Compose Configuration

Create `docker/docker-compose.yml`:

```yaml
version: '3.8'

services:
  # MySQL Database
  darkeden-mysql:
    image: mysql:5.7
    container_name: darkeden-mysql
    volumes:
      - mysql-data:/var/lib/mysql
      - ../initdb:/docker-entrypoint-initdb.d
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword123
      MYSQL_DATABASE: DARKEDEN
    command: >
      mysqld
      --sql_mode="ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"
      --character-set-server=utf8mb4
      --collation-server=utf8mb4_unicode_ci
    restart: unless-stopped
    networks:
      - darkeden-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-prootpassword123"]
      interval: 10s
      timeout: 5s
      retries: 5

  # DarkEden Game Server
  darkeden-server:
    image: darkeden:latest
    container_name: darkeden-server
    build:
      context: ..
      dockerfile: Dockerfile.pub
    command: ["sleep", "infinity"]
    depends_on:
      darkeden-mysql:
        condition: service_healthy
    volumes:
      - ../bin:/home/darkeden/vs/bin
      - ../data:/home/darkeden/vs/data
      - ./conf:/home/darkeden/vs/conf
    ports:
      - "9999:9999"   # Login Server TCP
      - "9998:9998"   # Game Server TCP
      - "9977:9977"   # Shared Server TCP
      - "9997:9997/udp"  # Game Server UDP
      - "9996:9996/udp"  # Login Server UDP
    networks:
      - darkeden-network
    restart: unless-stopped

  # PHP-FPM for Web Panel
  darkeden-php:
    image: php:8.1-fpm
    container_name: darkeden-php
    volumes:
      - ../web:/var/www/html
      - ./php/php.ini:/usr/local/etc/php/php.ini
    depends_on:
      - darkeden-mysql
    networks:
      - darkeden-network
    restart: unless-stopped

  # Nginx Web Server
  darkeden-nginx:
    image: nginx:alpine
    container_name: darkeden-nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ../web:/var/www/html
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - darkeden-php
    networks:
      - darkeden-network
    restart: unless-stopped

volumes:
  mysql-data:
    driver: local

networks:
  darkeden-network:
    driver: bridge
```

## Configuration Files

### 1. Update Docker Config Files

**`docker/conf/loginserver.conf`** - Update credentials:
```conf
DB_HOST : darkeden-mysql
DB_PORT : 3306
DB_DB : DARKEDEN
DB_USER : denostaugia
DB_PASSWORD : denostaugia2024

UI_DB_HOST : darkeden-mysql
UI_DB_PORT : 3306
UI_DB_DB : USERINFO
UI_DB_USER : denostaugia
UI_DB_PASSWORD : denostaugia2024

DIST_DB_HOST : darkeden-mysql
DIST_DB_PORT : 3306
DIST_DB_DB : DARKEDEN
DIST_DB_USER : denostaugia
DIST_DB_PASSWORD : denostaugia2024
```

**`docker/conf/gameserver.conf`** - Update credentials:
```conf
DB_HOST : darkeden-mysql
DB_PORT : 3306
DB_DB : DARKEDEN
DB_USER : denostaugia
DB_PASSWORD : denostaugia2024

UI_DB_HOST : darkeden-mysql
UI_DB_PORT : 3306
UI_DB_DB : USERINFO
UI_DB_USER : denostaugia
UI_DB_PASSWORD : denostaugia2024
```

**`docker/conf/sharedserver.conf`** - Update credentials (check file and update similarly)

### 2. Create Nginx Configuration

**`docker/nginx/default.conf`**:
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html;
    index index.php index.html;

    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass darkeden-php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

**`docker/nginx/nginx.conf`**:
```nginx
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    sendfile on;
    keepalive_timeout 65;

    include /etc/nginx/conf.d/*.conf;
}
```

### 3. Create PHP Configuration

**`docker/php/php.ini`**:
```ini
[PHP]
engine = On
short_open_tag = Off
precision = 14
output_buffering = 4096
zlib.output_compression = Off
implicit_flush = Off
unserialize_callback_func =
serialize_precision = -1
disable_functions =
disable_classes =
zend.enable_gc = On

expose_php = Off
max_execution_time = 30
max_input_time = 60
memory_limit = 128M
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
display_errors = Off
display_startup_errors = Off
log_errors = On
log_errors_max_len = 1024
ignore_repeated_errors = Off
ignore_repeated_source = Off
report_memleaks = On
track_errors = Off
html_errors = On
error_log = /var/log/php_errors.log

[Date]
date.timezone = UTC

[mysqli]
mysqli.max_persistent = -1
mysqli.allow_persistent = On
mysqli.max_links = -1
mysqli.default_port = 3306
mysqli.default_socket =
mysqli.default_host =
mysqli.default_user =
mysqli.default_pw =
mysqli.reconnect = Off
```

## Directory Structure

```
server/
├── docker/
│   ├── conf/
│   │   ├── loginserver.conf
│   │   ├── gameserver.conf
│   │   └── sharedserver.conf
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── default.conf
│   ├── php/
│   │   └── php.ini
│   └── docker-compose.yml
├── initdb/
│   ├── a-setup.sql
│   ├── DARKEDEN.sql
│   └── USERINFO.sql
├── web/              # Your web panel files (DK2WEB)
│   ├── index.php
│   ├── config.php
│   └── ...
├── bin/              # Compiled server binaries
├── data/             # Game data files
└── src/              # Source code
```

## Build the Server Binaries

If you haven't built the binaries yet:

```bash
# Build the development Docker image
docker build -t darkeden:dev -f Dockerfile.dev .

# Run the build container
docker run -v $(pwd):/home/darkeden/vs -it darkeden:dev /bin/bash

# Inside the container, compile:
cd /home/darkeden/vs/src
make -j$(nproc)

# Exit container - binaries will be in bin/ directory
exit

# Build the production image
docker build -t darkeden:latest -f Dockerfile.pub .
```

## Management Commands

### Start all services:
```bash
docker-compose -f docker/docker-compose.yml up -d
```

### Stop all services:
```bash
docker-compose -f docker/docker-compose.yml down
```

### View logs:
```bash
# All services
docker-compose -f docker/docker-compose.yml logs -f

# Specific service
docker logs -f darkeden-server
docker logs -f darkeden-nginx
docker logs -f darkeden-mysql
```

### Start game servers:
```bash
docker exec -d darkeden-server /home/darkeden/vs/bin/start.sh
```

### Stop game servers:
```bash
docker exec darkeden-server pkill -f gameserver
docker exec darkeden-server pkill -f sharedserver
docker exec darkeden-server pkill -f loginserver
```

### Access MySQL:
```bash
docker exec -it darkeden-mysql mysql -u denostaugia -p'denostaugia2024'
```

### Access server container:
```bash
docker exec -it darkeden-server /bin/bash
```

### Access PHP container:
```bash
docker exec -it darkeden-php /bin/bash
```

## Update Server IP

After starting, update the server IP in the database:

```bash
# Get your droplet's public IP
PUBLIC_IP=$(curl -s ifconfig.me)

# Update database
docker exec -it darkeden-mysql mysql -u denostaugia -p'denostaugia2024' DARKEDEN <<EOF
UPDATE GameServerInfo SET IP = '$PUBLIC_IP';
UPDATE WorldDBInfo SET IP = '$PUBLIC_IP';
SELECT * FROM GameServerInfo;
SELECT * FROM WorldDBInfo;
EOF
```

## Web Panel Configuration

Update `web/config.php` (or your web panel config):

```php
<?php
$host = "darkeden-mysql";  // Docker service name
$user = "denostaugia";
$pass = "denostaugia2024";
$db_name = "DARKEDEN";
// ... rest of config
?>
```

## Firewall Configuration

On your Digital Ocean droplet, ensure these ports are open:

```bash
# UFW (if enabled)
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 9999/tcp
ufw allow 9998/tcp
ufw allow 9977/tcp
ufw allow 9997/udp
ufw allow 9996/udp
```

Also configure Digital Ocean Firewall in the dashboard:
- TCP: 80, 443, 9999, 9998, 9977
- UDP: 9997, 9996

## Troubleshooting

### MySQL connection issues:
```bash
# Check if MySQL is running
docker ps | grep mysql

# Check MySQL logs
docker logs darkeden-mysql

# Test connection
docker exec -it darkeden-mysql mysql -u denostaugia -p'denostaugia2024' -e "SELECT 1"
```

### Server won't start:
```bash
# Check server logs
docker logs darkeden-server

# Check if servers are running inside container
docker exec darkeden-server ps aux | grep -E "login|shared|game"
```

### Web panel not loading:
```bash
# Check Nginx logs
docker logs darkeden-nginx

# Check PHP logs
docker logs darkeden-php

# Test PHP
docker exec darkeden-php php -v

# Test database connection from PHP container
docker exec darkeden-php php -r "echo mysqli_connect('darkeden-mysql', 'denostaugia', 'denostaugia2024', 'DARKEDEN') ? 'Connected' : 'Failed';"
```

### Port conflicts:
```bash
# Check what's using the ports
netstat -tulpn | grep -E "9999|9998|80"

# Stop conflicting services or change ports in docker-compose
```

## Security Notes

1. **Change default passwords:**
   - MySQL root password: `rootpassword123` → Change in `docker-compose.yml`
   - Database user: Already using `denostaugia`/`denostaugia2024` (change if needed)

2. **SSL/HTTPS:**
   - Add SSL certificates to Nginx for production
   - Use Let's Encrypt with Certbot in a separate container

3. **Firewall:**
   - Only expose necessary ports
   - Consider using Digital Ocean Firewall for additional security

4. **Backups:**
   - Regularly backup MySQL data volume
   - Backup web panel files

## Backup and Restore

### Backup MySQL:
```bash
docker exec darkeden-mysql mysqldump -u denostaugia -p'denostaugia2024' --all-databases > backup.sql
```

### Restore MySQL:
```bash
docker exec -i darkeden-mysql mysql -u denostaugia -p'denostaugia2024' < backup.sql
```

## Next Steps

1. Configure SSL/HTTPS for the web panel
2. Set up automated backups
3. Configure monitoring and alerts
4. Set up log rotation
5. Configure domain name and DNS

## Quick Reference

**Database Credentials:**
- User: `denostaugia`
- Password: `denostaugia2024`
- Databases: `DARKEDEN`, `USERINFO`

**Container Names:**
- `darkeden-mysql` - MySQL database
- `darkeden-server` - Game servers
- `darkeden-nginx` - Web server
- `darkeden-php` - PHP-FPM

**Important Ports:**
- 80 - HTTP (Web panel)
- 443 - HTTPS (Web panel)
- 9999 - Login Server
- 9998 - Game Server
- 9977 - Shared Server
- 9997/udp - Game Server UDP
- 9996/udp - Login Server UDP

