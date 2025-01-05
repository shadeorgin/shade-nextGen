# FTP Deployment

This guide explains how to deploy the SHaDE NextGen project to the production server.

## Setup

1. Install lftp:
- MacOS: `brew install lftp`
- Ubuntu: `sudo apt-get install lftp`

2. Create your .env file:
```bash
cp .env.template .env
```

3. Update credentials in .env:
```
FTP_HOST=shade.org.in
FTP_PORT=21
FTP_USER=your_username
FTP_PASS=your_password
REMOTE_PATH=public_html/shade-nextGen
```

4. Make deploy script executable:
```bash
chmod +x deploy.sh
```

## Deploy
```bash
./deploy.sh
```

# FTP Deployment Guide

This guide explains how to use the automated FTP deployment system for the SHaDE NextGen project.

## Prerequisites

1. Install `lftp` on your system:
- MacOS: `brew install lftp`
- Ubuntu/Debian: `sudo apt-get install lftp`
- Other Linux: Use your package manager to install `lftp`

2. Ensure you have FTP credentials for the server

## Setup Instructions

1. Copy the environment template to create your local configuration:
```bash
cp .env.template .env
```

2. Edit `.env` with your actual FTP credentials:
```
FTP_HOST=shade.org.in
FTP_PORT=21
FTP_USER=your_actual_username
FTP_PASS=your_actual_password
REMOTE_PATH=public_html/shade-nextGen
```

3. Make the deployment script executable:
```bash
chmod +x deploy.sh
```

## Usage

Run from the project root:
```bash
./ftp/deploy.sh
```

This will:
- Upload all files to the remote server
- Exclude files listed in .ftpignore
- Set correct file permissions
- Remove old files not in local copy

