#!/bin/bash
# Script to configure SSL in Docker Nginx

echo "=========================================="
echo "Configuring SSL for Docker Nginx"
echo "=========================================="
echo ""

# Get paths relative to script location
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
NGINX_CONF="$SCRIPT_DIR/nginx/default.conf"
SSL_CERT_PATH="/etc/letsencrypt/live/denostaugia.com"

# Check if certificates exist
echo "1. Checking SSL certificates..."
if [ ! -d "$SSL_CERT_PATH" ]; then
    echo "❌ ERROR: SSL certificates not found at $SSL_CERT_PATH"
    echo "You need to run Certbot first to generate certificates."
    exit 1
fi

if [ ! -f "$SSL_CERT_PATH/fullchain.pem" ]; then
    echo "❌ ERROR: fullchain.pem not found"
    exit 1
fi

if [ ! -f "$SSL_CERT_PATH/privkey.pem" ]; then
    echo "❌ ERROR: privkey.pem not found"
    exit 1
fi

echo "✓ SSL certificates found"
echo ""

# Backup current config
echo "2. Backing up current config..."
if [ -f "$NGINX_CONF" ]; then
    cp "$NGINX_CONF" "${NGINX_CONF}.backup.$(date +%Y%m%d_%H%M%S)"
    echo "✓ Backup created"
else
    echo "⚠️  Config file not found, will create new one"
fi
echo ""

# Create new config with SSL
echo "3. Creating SSL configuration..."
cat > "$NGINX_CONF" << 'EOF'
# HTTP - Redirect to HTTPS
server {
    listen 80;
    server_name denostaugia.com www.denostaugia.com;
    return 301 https://$server_name$request_uri;
}

# HTTPS
server {
    listen 443 ssl http2;
    server_name denostaugia.com www.denostaugia.com;
    
    root /var/www/html;
    index index.php index.html;

    # SSL Configuration (mounted from host)
    ssl_certificate /etc/letsencrypt/live/denostaugia.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/denostaugia.com/privkey.pem;
    ssl_trusted_certificate /etc/letsencrypt/live/denostaugia.com/chain.pem;
    
    # SSL Security Settings
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

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
EOF

echo "✓ Configuration file created"
echo ""

# Test Nginx config
echo "4. Testing Nginx configuration..."
docker exec darkeden-nginx nginx -t

if [ $? -ne 0 ]; then
    echo "❌ ERROR: Nginx configuration test failed!"
    echo "Restoring backup..."
    if [ -f "${NGINX_CONF}.backup"* ]; then
        cp "${NGINX_CONF}.backup"* "$NGINX_CONF"
    fi
    exit 1
fi

echo "✓ Configuration test passed"
echo ""

# Restart Nginx
echo "5. Restarting Nginx container..."
docker restart darkeden-nginx

# Wait a moment
sleep 2

# Test
echo ""
echo "6. Testing HTTPS..."
curl -k -I https://localhost 2>&1 | head -5

echo ""
echo "=========================================="
echo "SSL Configuration Complete!"
echo "=========================================="
echo ""
echo "Test URLs:"
echo "  https://denostaugia.com"
echo "  https://64.225.30.8"
echo ""
echo "Note: HTTP will redirect to HTTPS"

