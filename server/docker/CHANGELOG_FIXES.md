# Changelog - Fixes Applied to Repository

This document lists all fixes that were applied to resolve issues encountered during deployment.

## ✅ Fixes Applied

### 1. SSL/HTTPS Configuration
**Issue**: HTTPS was not working - connection refused  
**Fix**: Updated `docker/nginx/default.conf` with SSL configuration

**Changes**:
- Added HTTPS server block (port 443)
- Configured SSL certificates from `/etc/letsencrypt/live/denostaugia.com/`
- Added HTTP to HTTPS redirect
- Configured SSL security settings (TLS 1.2/1.3, secure ciphers)

**Files Modified**:
- `docker/nginx/default.conf`

### 2. SSL Certificate Mount
**Issue**: Docker Nginx couldn't access SSL certificates  
**Fix**: Added SSL certificate mount to `docker-compose.yml`

**Changes**:
- Added volume mount: `/etc/letsencrypt:/etc/letsencrypt:ro`

**Files Modified**:
- `docker/docker-compose.yml` (line 80)

### 3. Nginx Configuration
**Issue**: 403 Forbidden errors  
**Fix**: Ensured proper Nginx configuration for PHP and file serving

**Configuration**:
- Root directory: `/var/www/html`
- Index files: `index.php index.html`
- PHP-FPM pass: `darkeden-php:9000`
- Proper try_files directive for clean URLs

**Files Modified**:
- `docker/nginx/default.conf`

## 📋 Configuration Summary

### Nginx Configuration (`docker/nginx/default.conf`)
- ✅ HTTP (port 80) - Redirects to HTTPS
- ✅ HTTPS (port 443) - SSL enabled with Let's Encrypt certificates
- ✅ PHP-FPM integration
- ✅ Proper file serving configuration

### Docker Compose (`docker/docker-compose.yml`)
- ✅ SSL certificates mounted: `/etc/letsencrypt:/etc/letsencrypt:ro`
- ✅ Ports exposed: 80 (HTTP), 443 (HTTPS)
- ✅ All services properly configured

## 🔧 How to Use

### Initial Setup
1. Build Docker image: `docker build -t darkeden:latest -f Dockerfile.pub .`
2. Start services: `cd docker && docker-compose up -d`

### SSL Certificates
SSL certificates must be generated using Certbot before starting Docker:
```bash
certbot certonly --standalone -d denostaugia.com -d www.denostaugia.com
```

Certificates will be automatically mounted into the Nginx container.

### Web Files
Web files should be placed in `../web/` directory (relative to `docker/` folder).

## ⚠️ Important Notes

1. **SSL Certificates**: Must exist at `/etc/letsencrypt/live/denostaugia.com/` on the host
2. **Domain Name**: Currently configured for `denostaugia.com` and `www.denostaugia.com`
   - To change, update `server_name` in `docker/nginx/default.conf`
3. **Web Directory**: Ensure `../web/` exists and contains your website files
4. **Host Nginx**: Should be stopped/disabled when using Docker Nginx

## 🐛 Issues Resolved

- ✅ HTTPS connection refused → Fixed with SSL configuration
- ✅ 403 Forbidden errors → Fixed with proper Nginx config
- ✅ SSL certificates not accessible → Fixed with volume mount
- ✅ HTTP not redirecting to HTTPS → Fixed with redirect rule

## 📝 Future Improvements

- Consider making domain name configurable via environment variable
- Add support for multiple domains
- Add health checks for Nginx container
- Consider adding rate limiting configuration

