#!/bin/bash

# Set error handling
set -e
trap 'echo "Error occurred at line $LINENO. Exit code: $?" >&2' ERR

# Create necessary directories
echo "Creating directories..."
mkdir -p assets/{css,js,images}

# Function to download file with error handling
download_file() {
    local url=$1
    local destination=$2
    echo "Downloading $(basename $destination)..."
    if curl -sSL "$url" -o "$destination"; then
        echo "Successfully downloaded $(basename $destination)"
    else
        echo "Failed to download $(basename $destination)" >&2
        exit 1
    fi
}

# Download Bootstrap CSS
download_file "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" \
            "assets/css/bootstrap.min.css"

# Download Bootstrap JS
download_file "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" \
            "assets/js/bootstrap.bundle.min.js"

# Download Bootstrap Icons CSS
download_file "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" \
            "assets/css/bootstrap-icons.css"

# Download placeholder images for carousel
echo "Downloading placeholder images..."
for i in {1..3}; do
    download_file "https://placehold.co/1200x400/198754/FFFFFF/png/text=Slide+$i" \
                "assets/images/slide$i.jpg"
done

echo "All assets downloaded and created successfully!"

# Make the downloaded files readable
chmod 644 assets/css/* assets/js/* assets/images/*

