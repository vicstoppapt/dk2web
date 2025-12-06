#!/bin/bash
# Quick script to start game servers

echo "=========================================="
echo "Starting DarkEden Game Servers"
echo "=========================================="
echo ""

SERVER_IP="167.71.106.232"
CONF_DIR="/opt/opendarkeden/server/docker/conf"

# Step 1: Update config files
echo "1. Updating server IP in config files..."
if [ -f "$CONF_DIR/gameserver.conf" ]; then
    # Update LoginServerIP
    sed -i "s/^LoginServerIP:.*/LoginServerIP: $SERVER_IP/" "$CONF_DIR/gameserver.conf"
    # Update SharedServerIP
    sed -i "s/^SharedServerIP :.*/SharedServerIP : $SERVER_IP/" "$CONF_DIR/gameserver.conf"
    echo "✓ gameserver.conf updated"
else
    echo "⚠️  gameserver.conf not found at $CONF_DIR"
fi
echo ""

# Step 2: Update database
echo "2. Updating database with server IP..."
docker exec darkeden-mysql mysql -u denostaugia -p'denostaugia2024' DARKEDEN << EOF
UPDATE GameServerInfo SET IP = '$SERVER_IP';
UPDATE WorldDBInfo SET Host = 'darkeden-mysql';
SELECT 'Database updated successfully' AS Status;
EOF

if [ $? -eq 0 ]; then
    echo "✓ Database updated"
else
    echo "❌ Database update failed"
    exit 1
fi
echo ""

# Step 3: Check if container is running
echo "3. Checking server container..."
if ! docker ps | grep -q darkeden-server; then
    echo "❌ ERROR: darkeden-server container is not running!"
    echo "Start it with: cd /opt/opendarkeden/server/docker && docker-compose up -d"
    exit 1
fi
echo "✓ Container is running"
echo ""

# Step 4: Start servers
echo "4. Starting game servers..."
docker exec -d darkeden-server /bin/bash -c "cd /home/darkeden/vs/bin && ./start.sh"

# Wait a moment
sleep 3

# Step 5: Verify servers started
echo "5. Verifying servers..."
LOGIN_RUNNING=$(docker exec darkeden-server ps aux | grep -c "[l]ogin")
SHARE_RUNNING=$(docker exec darkeden-server ps aux | grep -c "[s]hare")
GAME_RUNNING=$(docker exec darkeden-server ps aux | grep -c "[g]ame")

if [ "$LOGIN_RUNNING" -gt 0 ]; then
    echo "✓ Login server is running"
else
    echo "❌ Login server is NOT running"
fi

if [ "$SHARE_RUNNING" -gt 0 ]; then
    echo "✓ Shared server is running"
else
    echo "❌ Shared server is NOT running"
fi

if [ "$GAME_RUNNING" -gt 0 ]; then
    echo "✓ Game server is running"
else
    echo "❌ Game server is NOT running"
fi
echo ""

# Step 6: Check ports
echo "6. Checking ports..."
docker exec darkeden-server netstat -tuln 2>/dev/null | grep -E "9999|9998|9977" || echo "⚠️  netstat not available, checking processes instead"
echo ""

# Step 7: Show logs
echo "7. Recent server logs:"
echo "--- Login Server ---"
docker exec darkeden-server tail -5 /home/darkeden/vs/bin/loginserver*.out 2>/dev/null || echo "No log file yet"
echo ""
echo "--- Shared Server ---"
docker exec darkeden-server tail -5 /home/darkeden/vs/bin/sharedserver*.out 2>/dev/null || echo "No log file yet"
echo ""
echo "--- Game Server ---"
docker exec darkeden-server tail -5 /home/darkeden/vs/bin/gameserver*.out 2>/dev/null || echo "No log file yet"
echo ""

echo "=========================================="
echo "Setup Complete!"
echo "=========================================="
echo ""
echo "Server IP: $SERVER_IP"
echo "Login Port: 9999"
echo "Game Port: 9998"
echo "Shared Port: 9977"
echo ""
echo "Configure your client with:"
echo "  ServerIP=$SERVER_IP"
echo "  LoginPort=9999"
echo "  GamePort=9998"
echo ""
echo "Test connection:"
echo "  telnet $SERVER_IP 9999"
echo "  telnet $SERVER_IP 9998"

