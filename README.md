# SQL Injection Demo Lab

A demonstration of SQL injection vulnerabilities using PHP and MySQL in Docker containers.

## Setup

1. Start the containers:
```bash
docker-compose up -d
```

2. Set up the database:
```bash
mysql -h 127.0.0.1 -P 33066 -u demo -pdemopass demo < setup.sql
```

3. Access the demo at: http://localhost:8080

## Demo Pages

### 🔓 Vulnerable Login (`vuln_login.php`)
- **Vulnerability**: Direct string concatenation in SQL queries
- **Demo Payload**: Username: `anything' OR '1'='1`, Password: anything
- **Result**: Authentication bypass

### 🔒 Safe Login (`safe_login.php`)
- **Security**: PDO prepared statements with bound parameters
- **Demo**: Same payloads will fail here
- **Result**: Proper authentication

### 🔍 Data Exfiltration (`search.php`)
- **Vulnerability**: UNION-based SQL injection in search queries
- **Demo Payload**: `' UNION SELECT username, password FROM users -- `
- **Result**: Database data extraction

### 👁️ Blind Boolean SQLi (`blind.php`)
- **Vulnerability**: Conditional logic vulnerable to boolean-based SQL injection
- **Demo Payloads**:
  - `1 OR 1=1` (always true)
  - `1 AND 1=2` (always false)
  - `1 AND substring((SELECT password FROM users WHERE id=1),1,1)='a'`

## Defense in Depth

### Code Level
- ✅ Use prepared statements (PDO, mysqli prepared)
- ✅ Input validation and sanitization
- ✅ Parameter binding, never concatenate user input
- ✅ Use ORM frameworks

### Database Level
- 🔒 Least privilege accounts
- 🚫 Disable dangerous features (stacked queries when not needed)
- 🎭 Use views and stored procedures

### Application Level
- 🛡️ Web Application Firewall (ModSecurity)
- 🔍 Input filtering and whitelisting
- 🏷️ Output encoding

### Operations Level
- 📊 Log monitoring for suspicious patterns
- 🚨 Failed login detection
- 🔬 Regular security audits and penetration testing

## Tools for Testing

- **sqlmap**: Automated SQL injection testing
- **OWASP ZAP**: Web application scanner
- **DVWA**: Damn Vulnerable Web Application
- **SQLNinja**: Microsoft SQL Server exploitation

## Warning

⚠️ **This is for educational purposes only**

- Only run this demo in a secure, isolated environment
- Never deploy vulnerable code in production
- Do not test on systems you don't own or have permission to test
- SQL injection can cause data breaches, system compromise, and data loss

## Cleanup

```bash
docker-compose down -v  # Remove containers and volumes
```

## Quick Start Commands

```bash
# Start lab
docker-compose up -d

# Setup database
mysql -h 127.0.0.1 -P 33066 -u demo -pdemopass demo < setup.sql

# Open browser
open http://localhost:8080

# Stop lab
docker-compose down
