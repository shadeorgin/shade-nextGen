#!/bin/bash

# Change to project root directory
cd "$(dirname "$0")/.." || exit

# Check if lftp is installed
if ! command -v lftp &> /dev/null; then
    echo "Error: lftp is not installed. Please install it first."
    echo "On MacOS: brew install lftp"
    echo "On Ubuntu: sudo apt-get install lftp"
    exit 1
fi

# Load environment variables from .env file
if [ ! -f ftp/.env ]; then
    echo "Error: ftp/.env file not found"
    echo "Please copy ftp/.env.template to ftp/.env and update with your credentials"
    exit 1
fi

source ftp/.env

# Create exclude pattern from .ftpignore
EXCLUDE_PATTERN=""
while IFS= read -r line || [[ -n "$line" ]]; do
    # Skip empty lines and comments
    [[ -z "$line" || "$line" =~ ^#.*$ ]] && continue
    EXCLUDE_PATTERN="$EXCLUDE_PATTERN -X $line"
done < ftp/.ftpignore

# Deploy using lftp
lftp -c "
    set ftp:ssl-allow no;
    set ssl:verify-certificate no;
    open -u $FTP_USER,$FTP_PASS -p $FTP_PORT $FTP_HOST;
    lcd $PWD;
    cd $REMOTE_PATH;
    mirror --reverse \
        --delete \
        --verbose \
        --parallel=4 \
        $EXCLUDE_PATTERN \
        ./ ./;
    chmod -R 755 ./;
    chmod -R 644 ./*.php;
    chmod -R 644 ./*.html;
    chmod -R 644 ./*.css;
    chmod -R 644 ./*.js;
"

echo "Deployment completed!"

