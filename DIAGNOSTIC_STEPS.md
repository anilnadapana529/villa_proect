# Owner Login Diagnostic Steps

## Problem
Still getting "Unexpected token '<'" error when testing owner-login

## Files to Upload

Upload these 3 new diagnostic files to your server:

1. **public/debug-owner.php** - Checks database structure and Owner model
2. **public/test-owner-login.php** - Tests owner login directly
3. **public/api-test.html** (updated) - Fixed API test tool

## Step-by-Step Diagnostic

### Step 1: Check Database & Model
Visit: `https://topmost.in/public/debug-owner.php`

This will check:
- ✓ Database connection
- ✓ Owners table structure (id, email, password, name columns)
- ✓ Number of owners in database
- ✓ Owner model loads correctly
- ✓ All required methods exist (login, stats, myVillas, bookings)
- ✓ Villas table has owner_id column
- ✓ Bookings table has owner_id column

**Expected Result:**
```json
{
  "status": "ALL_CHECKS_PASSED",
  "message": "Everything looks good!"
}
```

**If you see errors:**
- `NO_OWNERS_IN_DATABASE` → You need to create an owner first
- `MISSING_OWNER_ID_COLUMN` → Run the database fix SQL
- `MISSING_OWNER_ID_IN_BOOKINGS` → Run the database fix SQL

---

### Step 2: Test Owner Login Directly
Visit: `https://topmost.in/public/test-owner-login.php?email=YOUR_EMAIL&password=YOUR_PASSWORD`

Replace YOUR_EMAIL and YOUR_PASSWORD with actual owner credentials.

**If successful, you'll see:**
```json
{
  "status": true,
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV...",
  "owner": {
    "id": 1,
    "email": "owner@example.com"
  }
}
```

**If failed:**
```json
{
  "status": false,
  "message": "Invalid credentials"
}
```

This means either:
- Wrong email/password
- No owner exists with that email
- Password hash doesn't match

---

### Step 3: Check for PHP Errors
Look at the `debug.captured_output` field in the responses.

If it shows HTML error messages, that's the cause of "Unexpected token '<'"!

Common causes:
1. **PHP syntax error** in Owner.php or OwnerController.php
2. **Missing autoloader** for some class
3. **Database connection error**
4. **Wrong file uploaded** (old version still on server)

---

## If Database Tables Are Missing owner_id

Run this SQL in phpMyAdmin:

```sql
-- Add owner_id to villas table
ALTER TABLE villas 
ADD COLUMN IF NOT EXISTS owner_id INT DEFAULT NULL,
ADD INDEX idx_owner_id (owner_id);

-- Add owner_id to bookings table  
ALTER TABLE bookings
ADD COLUMN IF NOT EXISTS owner_id INT DEFAULT NULL,
ADD INDEX idx_owner_id_bookings (owner_id);

-- Update existing villas to have an owner (optional)
-- UPDATE villas SET owner_id = 1 WHERE owner_id IS NULL;

-- Update existing bookings with owner_id from villas
UPDATE bookings b
JOIN villas v ON b.villa_id = v.id
SET b.owner_id = v.owner_id
WHERE b.owner_id IS NULL;
```

---

## If No Owners Exist

Create one in phpMyAdmin:

```sql
-- Create a test owner
INSERT INTO owners (name, email, password, phone, created_at)
VALUES (
  'Test Owner',
  'owner@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
  '1234567890',
  NOW()
);
```

Login with:
- Email: `owner@example.com`
- Password: `password`

---

## Quick Checklist

- [ ] routes.php has the `exit;` fix (check test-routes.php)
- [ ] Upload debug-owner.php
- [ ] Upload test-owner-login.php
- [ ] Upload updated api-test.html
- [ ] Visit debug-owner.php - check all passes
- [ ] Visit test-owner-login.php with credentials
- [ ] If successful, try api-test.html
- [ ] If still fails, check `captured_output` field for PHP errors

---

## Next Steps

Once test-owner-login.php works, the real `/api/owner-login` endpoint should also work!

If test-owner-login.php works but /api/owner-login doesn't, the issue is in:
- routes.php (route not defined correctly)
- AuthController.php (ownerLogin method)

Let me know what you find!
