# DIAGNOSTIC STEPS - Find the Exact Error

Since you've uploaded the files but still getting errors, we need to find the exact issue.

## Step 1: Test Basic File Upload

Visit: `https://topmost.in/public/test.php`

This will show:
- Which files exist
- Which classes can load
- What methods are available
- Any PHP errors

## Step 2: Check Routes File

Visit: `https://topmost.in/public/test-routes.php`

Should show: `"status": "FIXED"`

If it shows `"NOT FIXED"` → The routes.php file upload failed or went to wrong location

## Step 3: Test Owner Login

**Request:**
```http
POST https://topmost.in/api/owner-login
Content-Type: application/json

{
  "email": "owner@example.com",
  "password": "yourpassword"
}
```

**Expected Response:**
```json
{
  "status": true,
  "token": "eyJ...",
  "owner": {...}
}
```

If you get HTML or error here → Owner login is broken

## Step 4: Test Owner Stats with Token

**Request:**
```http
GET https://topmost.in/api/owner-stats
Authorization: Bearer YOUR_TOKEN_FROM_STEP_3
```

**Expected Response:**
```json
{
  "status": true,
  "stats": {...}
}
```

If you get "Unexpected token '<'" here → There's a PHP error in the endpoint

## Step 5: Check PHP Error Logs

### Via cPanel:
1. Go to cPanel
2. Click "Errors" under "Metrics"
3. Look for recent PHP errors
4. Copy the exact error message

### Via File Manager:
Check these log files:
- `/home/username/logs/error_log`
- `/public_html/error_log`

## Common Issues & Solutions

### Issue 1: Wrong Upload Directory
**Symptom:** test-routes.php shows "NOT FIXED"

**Solution:** Make sure you uploaded to the correct folder:
- routes.php should be in the PROJECT ROOT (same level as App/ folder)
- NOT in /public folder
- NOT in /public_html folder

Check your folder structure:
```
/home/username/
  └── public_html/ (or villa-booking/)
      ├── routes.php ← HERE
      ├── App/
      │   └── Models/
      │       ├── Owner.php ← HERE
      │       ├── Admin.php
      │       etc...
      └── public/
          └── index.php
```

### Issue 2: File Permissions
**Symptom:** 500 Internal Server Error

**Solution:** Set correct permissions:
```bash
chmod 644 routes.php
chmod 644 App/Models/*.php
chmod 755 App/
chmod 755 App/Models/
```

### Issue 3: PHP Cache
**Symptom:** Files uploaded but old code still running

**Solution:**
1. cPanel → Select PHP Version → Reset to Default
2. Or restart PHP-FPM
3. Or add this to .htaccess:
```apache
<IfModule mod_php.c>
    php_flag opcache.enable Off
</IfModule>
```

### Issue 4: Wrong URL Format
**Symptom:** 404 Not Found

**Correct URLs:**
- ✅ `https://topmost.in/api/owner-stats`
- ✅ `https://topmost.in/public/api/owner-stats`
- ❌ `https://topmost.in/owner-stats` (missing /api/)
- ❌ `https://topmost.in/api/owner/stats` (wrong format)

### Issue 5: Missing Authorization Header
**Symptom:** "Unauthorized" error

**Solution:** Make sure your request includes:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

NOT:
- ❌ `Token: YOUR_JWT_TOKEN`
- ❌ `Authorization: YOUR_JWT_TOKEN` (missing "Bearer ")
- ❌ `Bearer YOUR_JWT_TOKEN` (missing "Authorization:")

## What to Share for Help

If still not working, run these tests and share the output:

1. **test.php output:**
   ```
   https://topmost.in/public/test.php
   ```

2. **test-routes.php output:**
   ```
   https://topmost.in/public/test-routes.php
   ```

3. **PHP error log** (last 20 lines):
   From cPanel Errors section

4. **Exact request you're making:**
   - Full URL
   - Request method (GET/POST)
   - Headers (especially Authorization)
   - Body (if POST)

5. **Exact error response:**
   - Full HTML/text response you're getting

With this information, I can pinpoint the exact issue.
