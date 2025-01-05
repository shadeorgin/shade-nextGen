# FTP Deployment Guide

This guide explains how to deploy the SHaDE NextGen project to the production server using FTP.

## Prerequisites

- Install lftp
    - MacOS: `brew install lftp`
    - Ubuntu: `sudo apt-get install lftp`

## Setup

1. Create your .env file:
```bash
cp ftp/.env.template ftp/.env
```

2. Edit ftp/.env with your credentials:
```
FTP_HOST=shade.org.in
FTP_PORT=21
FTP_USER=your_username
FTP_PASS=your_password
REMOTE_PATH=public_html/shade-nextGen
```

3. Make sure deploy.sh is executable:
```bash
chmod +x ftp/deploy.sh
```

## Deploy

Run the deployment script from the project root:
```bash
./ftp/deploy.sh
```

The script will:
- Upload all files to the remote server
- Exclude files specified in .ftpignore
- Set proper file permissions
- Clean up old files with --delete option

## Configuration

- .ftpignore: Specifies which files/directories to exclude from deployment
- .env: Contains FTP credentials (never commit this file)
- deploy.sh: The main deployment script

## Troubleshooting

If deployment fails:
1. Check your FTP credentials in .env
2. Ensure the remote directory exists
3. Check your connection to the FTP server
4. Verify lftp is installed correctly

