#!/bin/sh
# Stop all DarkEden game servers

echo "Stopping DarkEden servers..."

# Function to stop a server process
stop_server() {
    local server_name=$1
    local process_name=$2
    
    if pgrep -f "$process_name" > /dev/null 2>&1; then
        echo "Stopping $server_name..."
        killall "$process_name" 2>/dev/null
        sleep 1
        
        # Force kill if still running
        if pgrep -f "$process_name" > /dev/null 2>&1; then
            echo "Force stopping $server_name..."
            killall -9 "$process_name" 2>/dev/null
            sleep 1
        fi
        
        if ! pgrep -f "$process_name" > /dev/null 2>&1; then
            echo "✓ $server_name stopped"
        else
            echo "⚠ Warning: $server_name may still be running"
        fi
    else
        echo "  $server_name is not running"
    fi
}

# Stop servers in reverse order (game -> share -> login)
stop_server "Game Server" "gameserver"
stop_server "Shared Server" "sharedserver"
stop_server "Login Server" "loginserver"

echo ""
echo "All servers stopped"
