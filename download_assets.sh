#!/bin/bash

# Set error handling
set -e
trap 'echo "Error occurred at line $LINENO. Exit code: $?" >&2' ERR

# Create necessary directories
echo "Creating directories..."
mkdir -p assets/{css,js,images,fonts}

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

# Download Bootstrap Icons CSS and fonts
BOOTSTRAP_ICONS_VERSION="1.11.1"
download_file "https://cdn.jsdelivr.net/npm/bootstrap-icons@${BOOTSTRAP_ICONS_VERSION}/font/bootstrap-icons.css" \
            "assets/css/bootstrap-icons.css"

# Download Bootstrap Icons fonts
download_file "https://cdn.jsdelivr.net/npm/bootstrap-icons@${BOOTSTRAP_ICONS_VERSION}/font/fonts/bootstrap-icons.woff" \
            "assets/fonts/bootstrap-icons.woff"
download_file "https://cdn.jsdelivr.net/npm/bootstrap-icons@${BOOTSTRAP_ICONS_VERSION}/font/fonts/bootstrap-icons.woff2" \
            "assets/fonts/bootstrap-icons.woff2"

# Download meaningful images for carousel
echo "Downloading carousel images..."
download_file "https://images.unsplash.com/photo-1488521787991-ed7bbaae773c" \
            "assets/images/food-aid.jpg"
download_file "https://images.unsplash.com/photo-1503676260728-1c00da094a0b" \
            "assets/images/education.jpg"
download_file "https://images.unsplash.com/photo-1469571486292-0ba58a3f068b" \
            "assets/images/disaster-relief.jpg"

echo "All assets downloaded and created successfully!"

# Make the downloaded files readable
chmod 644 assets/css/* assets/js/* assets/images/* assets/fonts/*
