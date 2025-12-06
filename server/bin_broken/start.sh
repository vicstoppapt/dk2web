#!/bin/sh
# Start all DarkEden game servers

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$SCRIPT_DIR" || exit 1

echo "=========================================="
echo "Starting DarkEden Game Servers"
echo "=========================================="
echo ""

# Stop any existing servers first
if [ -f "./stop.sh" ]; then
    echo "Stopping existing servers..."
    ./stop.sh
    echo ""
fi

# Clean up old log files
echo "Cleaning up old log files..."
rm -f loginserver*.out 2>/dev/null
rm -f gameserver*.out 2>/dev/null
rm -f sharedserver*.out 2>/dev/null
echo "✓ Log files cleaned"
echo ""

# Wait a moment for processes to fully terminate
sleep 2

# Function to check if server is running
check_server() {
    local server_name=$1
    local process_name=$2
    
    if pgrep -f "$process_name" > /dev/null 2>&1; then
        echo "✓ $server_name is running"
        return 0
    else
        echo "✗ $server_name is NOT running"
        return 1
    fi
}

# Start Login Server
echo "Starting Login Server..."
if ./login; then
    sleep 2
    if ! check_server "Login Server" "loginserver"; then
        echo "  Error: Login server failed to start. Check log file." >&2
    fi
else
    echo "  Error: Failed to execute login script" >&2
fi
echo ""

# Start Shared Server
echo "Starting Shared Server..."
if ./share; then
    sleep 2
    if ! check_server "Shared Server" "sharedserver"; then
        echo "  Error: Shared server failed to start. Check log file." >&2
    fi
else
    echo "  Error: Failed to execute share script" >&2
fi
echo ""

# Start Game Server
echo "Starting Game Server..."
if ./game; then
    sleep 2
    if ! check_server "Game Server" "gameserver"; then
        echo "  Error: Game server failed to start. Check log file." >&2
    fi
else
    echo "  Error: Failed to execute game script" >&2
fi
echo ""

# Final status check
echo "=========================================="
echo "Final Server Status:"
echo "=========================================="
LOGIN_RUNNING=$(pgrep -f "loginserver" | wc -l)
SHARE_RUNNING=$(pgrep -f "sharedserver" | wc -l)
GAME_RUNNING=$(pgrep -f "gameserver" | wc -l)

check_server "Login Server" "loginserver"
check_server "Shared Server" "sharedserver"
check_server "Game Server" "gameserver"

echo ""
if [ "$LOGIN_RUNNING" -gt 0 ] && [ "$SHARE_RUNNING" -gt 0 ] && [ "$GAME_RUNNING" -gt 0 ]; then
    echo "✓ ALL SERVERS STARTED SUCCESSFULLY"
    echo ""
    echo "Log files:"
    ls -lh *.out 2>/dev/null | awk '{print "  " $9 " (" $5 ")"}'
else
    echo "⚠ WARNING: Some servers may not be running"
    echo "  Check log files for errors:"
    ls -lh *.out 2>/dev/null | awk '{print "    " $9}'
    exit 1
fi
echo "=========================================="
