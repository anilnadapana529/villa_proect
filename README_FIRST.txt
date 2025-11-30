═══════════════════════════════════════════════════════════════
           OWNER LOGIN TROUBLESHOOTING GUIDE
═══════════════════════════════════════════════════════════════

PROBLEM: Getting "Unexpected token '<'" error when testing 
         owner-login endpoint

CAUSE: PHP is outputting HTML error before JSON, causing
       JSON parsing to fail

═══════════════════════════════════════════════════════════════

SOLUTION: Upload diagnostic files to find the exact error
──────────────────────────────────────────────────────────────

STEP 1: Upload These 3 Files
────────────────────────────

From this project folder, upload to your server:

1. public/debug-owner.php       → Tests database & model
2. public/test-owner-login.php  → Tests login directly  
3. public/api-test.html         → API testing tool (updated)

Upload Location:
  /public_html/public/   (or wherever your public folder is)

═══════════════════════════════════════════════════════════════

STEP 2: Run Diagnostics
────────────────────────

Visit these URLs in your browser:

1. https://topmost.in/public/debug-owner.php
   
   This checks:
   ✓ Database connection
   ✓ Owners table structure
   ✓ Owner model loads correctly
   ✓ Required columns exist
   
   Look for: "status": "ALL_CHECKS_PASSED"
   
   If you see errors, that's your problem!

2. https://topmost.in/public/test-owner-login.php?email=YOUR_EMAIL&password=YOUR_PASSWORD
   
   Replace YOUR_EMAIL and YOUR_PASSWORD with real credentials
   
   This tests login without going through routing
   
   If this WORKS: Problem is in routes.php or autoloader
   If this FAILS: Problem is in Owner model or database

═══════════════════════════════════════════════════════════════

MOST LIKELY ISSUES:
───────────────────

1. NO OWNERS IN DATABASE
   → Create one using phpMyAdmin (see below)

2. MISSING owner_id COLUMN
   → Run SQL fixes (see below)

3. PHP SYNTAX ERROR
   → Check "captured_output" field in test results
   → Will show the exact error

4. WRONG CREDENTIALS
   → Make sure you're using correct email/password

═══════════════════════════════════════════════════════════════

QUICK FIXES:
────────────

Fix #1: Create a Test Owner
────────────────────────────

Run this in phpMyAdmin:

INSERT INTO owners (name, email, password, phone, created_at)
VALUES (
  'Test Owner',
  'owner@example.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  '1234567890',
  NOW()
);

Login credentials:
  Email: owner@example.com
  Password: password


Fix #2: Add owner_id Columns
─────────────────────────────

Run this in phpMyAdmin:

ALTER TABLE villas 
ADD COLUMN IF NOT EXISTS owner_id INT DEFAULT NULL,
ADD INDEX idx_owner_id (owner_id);

ALTER TABLE bookings
ADD COLUMN IF NOT EXISTS owner_id INT DEFAULT NULL,
ADD INDEX idx_owner_id_bookings (owner_id);

UPDATE bookings b
JOIN villas v ON b.villa_id = v.id
SET b.owner_id = v.owner_id
WHERE b.owner_id IS NULL;

═══════════════════════════════════════════════════════════════

AFTER RUNNING DIAGNOSTICS:
──────────────────────────

Send me the output from:
  https://topmost.in/public/debug-owner.php

And I'll tell you exactly what to fix!

═══════════════════════════════════════════════════════════════
