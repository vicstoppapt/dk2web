#!/bin/bash
# Diagnostic script for 403 Forbidden error

echo "=========================================="
echo "403 Forbidden Diagnostic"
echo "=========================================="
echo ""

# Get server directory (parent of docker/)
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SERVER_DIR="$(dirname "$SCRIPT_DIR")"
WEB_DIR="$SERVER_DIR/web"
 
echo "1. Checking web directory on host..."
if [ -d "$WEB_DIR" ]; then
    echo "✓ $WEB_DIR exists"
    echo "   Contents:"
    ls -la "$WEB_DIR"/ | head -10
else
    echo "✗ $WEB_DIR does NOT exist"
fi
echo ""

echo "2. Checking /var/www/denostaugia.com..."
if [ -d "/var/www/denostaugia.com" ]; then
    echo "✓ /var/www/denostaugia.com exists"
    echo "   Contents:"
    ls -la /var/www/denostaugia.com/ | head -10
else
    echo "✗ /var/www/denostaugia.com does NOT exist"
fi
echo ""

echo "3. Checking what Nginx container sees..."
docker exec darkeden-nginx ls -la /var/www/html/ 2>/dev/null || echo "Cannot access container"
echo ""

echo "4. Checking for index files in container..."
docker exec darkeden-nginx ls -la /var/www/html/index.* 2>/dev/null || echo "No index files found"
echo ""

echo "5. Checking Nginx error logs..."
echo "--- Last 10 lines ---"
docker logs darkeden-nginx 2>&1 | grep -i "error\|forbidden\|403" | tail -10
echo ""

echo "6. Testing Nginx response..."
curl -I http://localhost 2>&1 | head -5
echo ""

echo "7. Checking Nginx config..."
docker exec darkeden-nginx cat /etc/nginx/conf.d/default.conf | grep -E "index|root|location /"
echo ""

echo "=========================================="
echo "Diagnostic Complete"
echo "=========================================="
echo ""
echo "Common fixes:"
echo "1. Create web directory: mkdir -p $WEB_DIR"
echo "2. Copy files: cp -r /var/www/denostaugia.com/* $WEB_DIR/"
echo "3. Or update docker-compose.yml to mount /var/www/denostaugia.com"

