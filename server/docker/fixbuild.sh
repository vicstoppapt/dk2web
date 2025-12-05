#!/bin/bash
# Quick script to build darkeden:latest image

echo "=========================================="
echo "Building DarkEden Docker Image"
echo "=========================================="
echo ""

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SERVER_DIR="$(dirname "$SCRIPT_DIR")"

echo "Server directory: $SERVER_DIR"
cd "$SERVER_DIR"

# Check if required directories exist
echo ""
echo "Checking required files..."
if [ ! -d "bin" ]; then
    echo "❌ ERROR: bin/ directory not found!"
    echo "You need to compile the server first."
    echo ""
    echo "Run:"
    echo "  docker build -t darkeden:dev -f Dockerfile.dev ."
    echo "  docker run -v \$(pwd):/home/darkeden/vs/ -it darkeden:dev /bin/bash"
    echo "  # Inside container: make"
    exit 1
fi

if [ ! -d "data" ]; then
    echo "❌ ERROR: data/ directory not found!"
    exit 1
fi

if [ ! -d "docker/conf" ]; then
    echo "❌ ERROR: docker/conf/ directory not found!"
    exit 1
fi

echo "✓ All required directories found"
echo ""

# Check if binaries exist
if [ -z "$(ls -A bin/ 2>/dev/null)" ]; then
    echo "⚠️  WARNING: bin/ directory is empty!"
    echo "The image will build but won't have server binaries."
    echo ""
    read -p "Continue anyway? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Build the image
echo ""
echo "Building darkeden:latest image..."
echo "Command: docker build -t darkeden:latest -f Dockerfile.pub ."
echo ""

docker build -t darkeden:latest -f Dockerfile.pub .

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "✓ Image built successfully!"
    echo "=========================================="
    echo ""
    echo "Verify image:"
    docker images | grep darkeden
    echo ""
    echo "Now you can run:"
    echo "  cd docker"
    echo "  docker-compose up -d"
else
    echo ""
    echo "=========================================="
    echo "❌ Build failed!"
    echo "=========================================="
    exit 1
fi

