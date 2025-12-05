# Security Audit Report - DK2WEB Panel

**Date**: 2025-01-27  
**Version**: Modernized DK2WEB  
**Auditor**: Security Review

---

## Executive Summary

This security audit identified **8 CRITICAL**, **5 HIGH**, and **3 MEDIUM** severity security vulnerabilities in the DK2WEB panel. The most critical issues are:

1. **Plaintext password storage** - Passwords stored in plaintext
2. **No password hashing** - Direct password comparison
3. **Admin panel unprotected** - No authentication required
4. **Hardcoded credentials** - Database credentials in config.php
5. **Missing CSRF protection** - Forms vulnerable to CSRF attacks
6. **Session fixation risk** - Sessions not regenerated on login
7. **SQL injection potential** - Some queries may be vulnerable
8. **XSS vulnerabilities** - Some output not properly escaped

---

## 🔴 CRITICAL VULNERABILITIES

### 1. Plaintext Password Storage
**File**: `config.php`, `login.php`, `reg/reg.php`  
**Severity**: CRITICAL  
**CWE**: CWE-256 (Plaintext Storage of Password)

**Issue**:
- Passwords are stored in plaintext in the database
- Direct password comparison: `if ($player['Password'] == $password)`

**Location**:
```php
// login.php line 27
if ($player['Password'] == $password) {
```

**Impact**: 
- If database is compromised, all passwords are exposed
- No protection against data breaches
- Violates security best practices

**Fix**:
```php
// Use password_hash() when storing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt->bind_param("sss", $playerid, $hashed_password, $ssn);

// Use password_verify() when checking
if (password_verify($password, $player['Password'])) {
```

---

### 2. No Password Hashing
**File**: `reg/reg.php`  
**Severity**: CRITICAL  
**CWE**: CWE-256

**Issue**:
- Passwords inserted directly into database without hashing
- Comment acknowledges issue but doesn't fix it

**Location**:
```php
// reg/reg.php line 41-42
// Note: Original used plaintext password - you should hash it
$stmt = $db->prepare("INSERT INTO Player (PlayerID, Password, SSN, LogOn, Access) VALUES (?, ?, ?, NOW(), 'N')");
$stmt->bind_param("sss", $playerid, $password, $ssn);
```

**Fix**: Implement password hashing (see above)

---

### 3. Admin Panel Unprotected
**File**: `admin.php`  
**Severity**: CRITICAL  
**CWE**: CWE-306 (Missing Authentication)

**Issue**:
- Admin panel has no authentication check
- Anyone can access admin functions
- Comment says "TODO: Add authentication" but not implemented

**Location**:
```php
// admin.php - No authentication check
require_once 'config.php';
// Direct access allowed!
```

**Impact**: 
- Unauthorized access to admin functions
- Potential data manipulation
- Server compromise

**Fix**:
```php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}
```

---

### 4. Hardcoded Database Credentials
**File**: `config.php`  
**Severity**: CRITICAL  
**CWE**: CWE-798 (Hardcoded Credentials)

**Issue**:
- Database credentials hardcoded in source code
- Credentials visible in version control
- No environment variable usage

**Location**:
```php
// config.php lines 12-15
$host = "localhost";
$user = "elcastle";
$pass = "elca110"; // ⚠️ Hardcoded password
$db = "DARKEDEN";
```

**Impact**:
- If code is leaked, database is compromised
- Cannot use different credentials per environment
- Security risk if repository is public

**Fix**:
```php
// Use environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'elcastle';
$pass = getenv('DB_PASS') ?: '';
$db = getenv('DB_NAME') ?: 'DARKEDEN';
```

---

### 5. Missing CSRF Protection
**File**: All form files (`login.php`, `reg/reg.php`, `shop/buy.php`, etc.)  
**Severity**: CRITICAL  
**CWE**: CWE-352 (Cross-Site Request Forgery)

**Issue**:
- No CSRF tokens on any forms
- Forms vulnerable to CSRF attacks
- Attackers can perform actions on behalf of users

**Impact**:
- Unauthorized account creation
- Unauthorized purchases
- Unauthorized character deletion
- Account takeover

**Fix**: Implement CSRF tokens:
```php
// Generate token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// In form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

---

### 6. Session Fixation Vulnerability
**File**: `login.php`  
**Severity**: CRITICAL  
**CWE**: CWE-384 (Session Fixation)

**Issue**:
- Session not regenerated after login
- Session ID doesn't change on authentication
- Vulnerable to session fixation attacks

**Location**:
```php
// login.php lines 28-31
session_start();
$_SESSION['playerid'] = $playerid;
$_SESSION['logged_in'] = true;
// Missing: session_regenerate_id(true);
```

**Fix**:
```php
session_start();
session_regenerate_id(true); // Regenerate session ID
$_SESSION['playerid'] = $playerid;
$_SESSION['logged_in'] = true;
```

---

### 7. Missing Input Validation on SSN
**File**: `reg/reg.php`  
**Severity**: CRITICAL  
**CWE**: CWE-20 (Improper Input Validation)

**Issue**:
- SSN field has no validation
- Only checks if empty, no format validation
- Could accept malicious input

**Location**:
```php
// reg/reg.php line 14
$ssn = clean_input($_POST['ssn'] ?? '');
// Only checks if empty, no format validation
```

**Fix**:
```php
// Validate SSN format (adjust based on your requirements)
if (!preg_match('/^[0-9]{9,11}$/', $ssn)) {
    $error = 'Invalid SSN format';
}
```

---

### 8. Error Messages Reveal Information
**File**: Multiple files  
**Severity**: CRITICAL  
**CWE**: CWE-209 (Information Exposure)

**Issue**:
- Database errors exposed to users
- Reveals database structure and queries
- Helps attackers understand system

**Location**:
```php
// reg/reg.php line 47
$error = 'Registration failed: ' . $db->error;
// delete_char.php line 40
die('Error deleting character: ' . $db->error);
```

**Fix**:
```php
// Log errors, show generic message to user
error_log('Registration failed: ' . $db->error);
$error = 'Registration failed. Please try again.';
```

---

## 🟠 HIGH SEVERITY VULNERABILITIES

### 9. Missing Rate Limiting
**File**: `login.php`, `reg/reg.php`  
**Severity**: HIGH  
**CWE**: CWE-307 (Improper Restriction of Excessive Authentication Attempts)

**Issue**:
- No rate limiting on login attempts
- No rate limiting on registration
- Vulnerable to brute force attacks

**Fix**: Implement rate limiting:
```php
// Track failed attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempts'] >= 5) {
    if (time() - $_SESSION['last_attempt'] < 300) { // 5 minutes
        $error = 'Too many login attempts. Please wait 5 minutes.';
    } else {
        $_SESSION['login_attempts'] = 0;
    }
}
```

---

### 10. Weak Password Requirements
**File**: `reg/reg.php`  
**Severity**: HIGH  
**CWE**: CWE-521 (Weak Password Requirements)

**Issue**:
- Minimum password length only 6 characters
- No complexity requirements
- No password strength validation

**Location**:
```php
// reg/reg.php line 21
} elseif (empty($password) || strlen($password) < 6) {
```

**Fix**:
```php
// Require stronger passwords
if (strlen($password) < 12 || 
    !preg_match('/[A-Z]/', $password) || 
    !preg_match('/[a-z]/', $password) || 
    !preg_match('/[0-9]/', $password)) {
    $error = 'Password must be at least 12 characters with uppercase, lowercase, and numbers';
}
```

---

### 11. Missing HTTPS Enforcement
**File**: All files  
**Severity**: HIGH  
**CWE**: CWE-319 (Cleartext Transmission)

**Issue**:
- No HTTPS enforcement
- Passwords transmitted in plaintext
- Session cookies not secure

**Fix**: Add to `.htaccess` or server config:
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Secure cookies
php_value session.cookie_secure 1
php_value session.cookie_httponly 1
```

---

### 12. SQL Injection Risk in Search
**File**: `players.php`  
**Severity**: HIGH  
**CWE**: CWE-89 (SQL Injection)

**Issue**:
- While using prepared statements, LIKE pattern could be exploited
- Search term uses `%$search%` which is safe, but worth reviewing

**Location**:
```php
// players.php line 15-17
$stmt = $db->prepare("SELECT PlayerID, Name, Level, Race, Fame FROM PlayerCreature WHERE Name LIKE ? OR PlayerID LIKE ? LIMIT 50");
$search_term = "%$search%";
$stmt->bind_param("ss", $search_term, $search_term);
```

**Status**: Actually safe due to prepared statements, but verify all queries

---

### 13. Missing Authorization Checks
**File**: `delete_char.php`, `jobchange/char.php`  
**Severity**: HIGH  
**CWE**: CWE-284 (Improper Access Control)

**Issue**:
- While ownership is verified, no additional authorization checks
- No confirmation for destructive actions
- Missing transaction safety

**Location**:
```php
// delete_char.php - Ownership verified but no confirmation
// jobchange/char.php - No double-check for expensive operations
```

**Fix**: Add confirmation dialogs and transaction safety

---

## 🟡 MEDIUM SEVERITY VULNERABILITIES

### 14. XSS in URL Parameters
**File**: `home.php`, `delete_char.php`  
**Severity**: MEDIUM  
**CWE**: CWE-79 (Cross-Site Scripting)

**Issue**:
- URL parameters used in links without additional validation
- While `htmlspecialchars()` is used, URL encoding should be verified

**Location**:
```php
// home.php line 56
<a href="delete_char.php?name=<?php echo urlencode($char['Name']); ?>">
```

**Status**: Actually safe due to `urlencode()`, but verify all instances

---

### 15. Missing Security Headers
**File**: All files  
**Severity**: MEDIUM  
**CWE**: CWE-693 (Protection Mechanism Failure)

**Issue**:
- No security headers set
- Missing X-Frame-Options, X-Content-Type-Options, etc.

**Fix**: Add to `header.php` or `.htaccess`:
```php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'');
```

---

### 16. Insecure Session Configuration
**File**: All files using sessions  
**Severity**: MEDIUM  
**CWE**: CWE-614 (Sensitive Cookie in HTTPS Session)

**Issue**:
- Session cookies not marked as HttpOnly
- Session cookies not marked as Secure
- Session timeout not configured

**Fix**: Configure in `php.ini` or `.htaccess`:
```apache
php_value session.cookie_httponly 1
php_value session.cookie_secure 1
php_value session.cookie_samesite Strict
php_value session.gc_maxlifetime 3600
```

---

## ✅ GOOD SECURITY PRACTICES FOUND

1. ✅ **Prepared Statements**: Most queries use prepared statements
2. ✅ **Input Sanitization**: `clean_input()` function used
3. ✅ **Output Escaping**: `htmlspecialchars()` used in most outputs
4. ✅ **Session Management**: Sessions used for authentication
5. ✅ **Direct Access Prevention**: `config.php` has direct access check

---

## Recommendations Priority

### Immediate (Before Production):
1. 🔴 Implement password hashing
2. 🔴 Add admin panel authentication
3. 🔴 Implement CSRF protection
4. 🔴 Regenerate session on login
5. 🔴 Move credentials to environment variables

### High Priority:
6. 🟠 Add rate limiting
7. 🟠 Enforce HTTPS
8. 🟠 Strengthen password requirements
9. 🟠 Add security headers

### Medium Priority:
10. 🟡 Improve error handling
11. 🟡 Configure secure session cookies
12. 🟡 Add input validation for SSN

---

## Security Checklist

- [ ] Passwords hashed with `password_hash()`
- [ ] Password verification with `password_verify()`
- [ ] Admin panel protected with authentication
- [ ] CSRF tokens on all forms
- [ ] Session regeneration on login
- [ ] Database credentials in environment variables
- [ ] Rate limiting on login/registration
- [ ] HTTPS enforced
- [ ] Security headers configured
- [ ] Session cookies secure and HttpOnly
- [ ] Error messages don't reveal system info
- [ ] Input validation on all fields
- [ ] Output escaping on all user data
- [ ] Authorization checks on all actions

---

## Testing Recommendations

1. **Penetration Testing**: Test all identified vulnerabilities
2. **Code Review**: Review all SQL queries for injection risks
3. **Authentication Testing**: Test login/logout/session management
4. **Authorization Testing**: Verify access controls work correctly
5. **Input Validation Testing**: Test all input fields with malicious data
6. **CSRF Testing**: Verify CSRF protection works

---

## Conclusion

The DK2WEB panel has several critical security vulnerabilities that **MUST** be fixed before production use. The most critical issues are plaintext password storage, missing authentication on admin panel, and lack of CSRF protection. 

**Recommendation**: Do not deploy to production until critical vulnerabilities are fixed.

---

## References

- OWASP Top 10: https://owasp.org/www-project-top-ten/
- CWE Database: https://cwe.mitre.org/
- PHP Security Best Practices: https://www.php.net/manual/en/security.php

