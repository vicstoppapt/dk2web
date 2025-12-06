#!/bin/bash
# Quick fix for 403 Forbidden - Copy web files to Docker location

echo "=========================================="
echo "Fixing 403 Forbidden Error"
echo "=========================================="
echo ""

# Get server directory (parent of docker/)
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SERVER_DIR="$(dirname "$SCRIPT_DIR")"
WEB_DIR="$SERVER_DIR/web"
SOURCE_DIR="/var/www/denostaugia.com"
 
# Check if source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo "❌ ERROR: $SOURCE_DIR does not exist!"
    echo ""
    echo "Please specify where your web files are:"
    read -p "Enter web files directory path: " SOURCE_DIR
    if [ ! -d "$SOURCE_DIR" ]; then
        echo "❌ Directory still doesn't exist. Exiting."
        exit 1
    fi
fi

echo "Source directory: $SOURCE_DIR"
echo "Target directory: $WEB_DIR"
echo ""

# Create web directory if it doesn't exist
if [ ! -d "$WEB_DIR" ]; then
    echo "Creating web directory..."
    mkdir -p "$WEB_DIR"
fi

# Copy files
echo "Copying web files..."
cp -r "$SOURCE_DIR"/* "$WEB_DIR"/

# Set permissions
echo "Setting permissions..."
chmod -R 755 "$WEB_DIR"
chown -R root:root "$WEB_DIR" 2>/dev/null || true

# Check if index file exists
if [ ! -f "$WEB_DIR/index.php" ] && [ ! -f "$WEB_DIR/index.html" ]; then
    echo "⚠️  WARNING: No index.php or index.html found!"
    echo "Creating test file..."
    echo "<?php phpinfo(); ?>" > "$WEB_DIR/index.php"
fi

# Restart Nginx
echo ""
echo "Restarting Nginx container..."
docker restart darkeden-nginx

# Wait a moment
sleep 2

# Test
echo ""
echo "Testing..."
curl -I http://localhost 2>&1 | head -5

echo ""
echo "=========================================="
echo "Done! Try accessing:"
echo "  http://167.71.106.232"
echo "=========================================="

