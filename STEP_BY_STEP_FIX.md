# STEP BY STEP FIX FOR "Unexpected token '<'" ERROR

## THE PROBLEM
Your server is returning HTML (PHP errors) instead of JSON. This means the fixed files haven't been uploaded yet.

## DIAGNOSTIC TESTS (Check these first!)

### Test 1: Check if routes.php has the fix
Visit: `https://topmost.in/test-routes.php`

Expected result:
```json
{
  "status": "FIXED",
  "has_exit_fix": true
}
```

If you see `"status": "NOT FIXED"` → You MUST upload routes.php

### Test 2: Debug owner endpoint
Visit: `https://topmost.in/debug-owner.php` (with Authorization header)

This will show you exactly where the error occurs.

---

## THE FIX - UPLOAD THESE 8 FILES

### Priority 1 - CRITICAL FILE (Must upload first!)
```
routes.php
```
Location on server: `/public_html/routes.php` or `/villa-booking/routes.php`

### Priority 2 - Model Files
```
App/Models/Admin.php
App/Models/Owner.php
App/Models/Booking.php
App/Models/Villa.php
App/Models/VillaImages.php
App/Models/OwnerStats.php
App/Models/UserStats.php
```

---

## HOW TO UPLOAD (Choose your method)

### Method 1: File Manager (cPanel)
1. Login to cPanel
2. Open File Manager
3. Navigate to your project directory
4. Upload `routes.php` to the root
5. Upload the App/Models/* files to `App/Models/` folder
6. Overwrite existing files

### Method 2: FTP
1. Open FileZilla or your FTP client
2. Connect to your server
3. Navigate to your project directory
4. Upload all 8 files
5. Overwrite existing files

### Method 3: SSH/Terminal
```bash
# Upload via SCP
scp routes.php user@topmost.in:/path/to/project/
scp App/Models/*.php user@topmost.in:/path/to/project/App/Models/
```

---

## VERIFY THE FIX

### Step 1: Test routes.php upload
```
https://topmost.in/test-routes.php
```
Should show: `"status": "FIXED"`

### Step 2: Login as Owner
```
POST https://topmost.in/api/owner-login
Body: {"email": "your@email.com", "password": "yourpass"}
```
Copy the token from response

### Step 3: Test Owner Stats
```
GET https://topmost.in/api/owner-stats
Header: Authorization: Bearer YOUR_TOKEN_HERE
```
Should return JSON with stats

### Step 4: Test Owner Villas
```
GET https://topmost.in/api/owner-villas
Header: Authorization: Bearer YOUR_TOKEN_HERE
```
Should return JSON with villas list

---

## STILL GETTING ERRORS?

If you're SURE you uploaded the files and still get errors:

1. **Clear cache**: Some servers cache PHP files
   - In cPanel: Go to "Select PHP Version" → "Reset to Default"
   - Or restart PHP-FPM

2. **Check file permissions**: Files should be 644
   ```bash
   chmod 644 routes.php
   chmod 644 App/Models/*.php
   ```

3. **Check PHP error logs**:
   - cPanel: Go to "Errors" in cPanel
   - Or check: `/home/username/logs/error_log`

4. **Verify file contents**: Open routes.php on server and check line 57-58:
   ```php
   if (!$auth["status"]) {
       Response::json(["status" => false, "message" => "Unauthorized"], 401);
       exit;  // ← THIS MUST BE THERE!
   }
   ```

---

## QUICK CHECKLIST

- [ ] Uploaded routes.php
- [ ] Uploaded all 7 App/Models/*.php files
- [ ] Visited test-routes.php and saw "FIXED"
- [ ] Tested owner-login and got token
- [ ] Tested owner-stats with token
- [ ] Tested owner-villas with token

---

## NEED MORE HELP?

Run the debug endpoint and share the output:
```
https://topmost.in/debug-owner.php
```
(Include Authorization header with your owner token)

This will show exactly which step is failing.
