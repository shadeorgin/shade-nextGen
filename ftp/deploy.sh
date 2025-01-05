#!/bin/bash

# Change to project root directory
cd "$(dirname "$0")/.." || exit

# Function to show usage
show_usage() {
    echo "Usage: $0 [options]"
    echo "Options:"
    echo "  -d, --dry-run     Show what would be uploaded without making changes"
    echo "  -b, --backup      Create a backup of remote files before deployment"
    echo "  -h, --help        Show this help message"
    exit 1
}

# Parse command line arguments
DRY_RUN=0
BACKUP=0
while [[ $# -gt 0 ]]; do
    case $1 in
        -d|--dry-run)
            DRY_RUN=1
            shift
            ;;
        -b|--backup)
            BACKUP=1
            shift
            ;;
        -h|--help)
            show_usage
            ;;
        *)
            echo "Unknown option: $1"
            show_usage
            ;;
    esac
done

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

# Validate required environment variables
for var in FTP_HOST FTP_PORT FTP_USER FTP_PASS REMOTE_PATH; do
    if [ -z "${!var}" ]; then
        echo "Error: $var is not set in ftp/.env"
        exit 1
    fi
done

# Create exclude pattern from .ftpignore
EXCLUDE_PATTERN=""
while IFS= read -r line || [[ -n "$line" ]]; do
    # Skip empty lines and comments
    [[ -z "$line" || "$line" =~ ^#.*$ ]] && continue
    EXCLUDE_PATTERN="$EXCLUDE_PATTERN -X $line"
done < ftp/.ftpignore

# Prepare LFTP commands
LFTP_COMMANDS="
    set ftp:ssl-allow no;
    set ssl:verify-certificate no;
    open -u $FTP_USER,$FTP_PASS -p $FTP_PORT $FTP_HOST;
    lcd $PWD;
    cd $REMOTE_PATH;
"

# Add backup command if requested
if [ $BACKUP -eq 1 ]; then
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    echo "Creating backup..."
    LFTP_COMMANDS="$LFTP_COMMANDS
        mirror --parallel=4 ./ ./backup_${TIMESTAMP}/;
    "
fi

# Add mirror command with appropriate flags
MIRROR_CMD="mirror --reverse --delete --verbose --parallel=4 $EXCLUDE_PATTERN ./ ./"
if [ $DRY_RUN -eq 1 ]; then
    MIRROR_CMD="$MIRROR_CMD --dry-run"
    echo "Performing dry run..."
else
    echo "Starting deployment..."
fi

LFTP_COMMANDS="$LFTP_COMMANDS
    $MIRROR_CMD;
"

# Add permission commands if not dry run
if [ $DRY_RUN -eq 0 ]; then
    LFTP_COMMANDS="$LFTP_COMMANDS
        find ./ -type d -exec chmod 755 {} \;
        find ./ -type f -name '*.php' -exec chmod 644 {} \;
        find ./ -type f -name '*.html' -exec chmod 644 {} \;
        find ./ -type f -name '*.css' -exec chmod 644 {} \;
        find ./ -type f -name '*.js' -exec chmod 644 {} \;
    "
fi

# Execute LFTP commands
lftp -c "$LFTP_COMMANDS"

if [ $DRY_RUN -eq 1 ]; then
    echo "Dry run completed. No changes were made."
else
    echo "Deployment completed successfully!"
fi
