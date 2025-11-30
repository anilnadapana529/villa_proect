# Testing Guide - Login & Register Issues

## Current Status

Pages are loading but login/register showing "Unauthorized" error.

## Test URLs Created

1. **API Test Page**: https://topmost.in/api-test.html
   - Tests user-register API
   - Tests user-login API
   - Shows exact API response

2. **Debug Routes**: https://topmost.in/debug-routes.php
   - Shows how URI is parsed
   - Shows endpoint detection

## Login Test Steps

1. Go to: https://topmost.in/web/pages/login.php
2. Enter credentials:
   - Email: (any existing user)
   - Password: (their password)
   - Role: User
3. Open browser console (F12)
4. Click Login
5. Check console for error messages

## Register Test Steps

1. Go to: https://topmost.in/web/pages/register.php
2. Fill form with new data
3. Open browser console (F12)
4. Click Sign Up
5. Check console for error messages

## Possible Issues & Solutions

### Issue 1: No Users in Database
**Solution**: Create a test user directly in database

### Issue 2: Endpoint Not Matching
**Check**: Visit debug-routes.php to see if endpoint is parsed correctly

### Issue 3: CORS Issue
**Check**: Look for CORS errors in browser console

### Issue 4: JWT Secret Missing
**Check**: .env file has JWT_SECRET

## Quick Database Check

Run this SQL to check if users table exists and has data:
```sql
SELECT * FROM users LIMIT 5;
```

## Create Test User Manually

```sql
INSERT INTO users (name, email, phone, password, created_at) 
VALUES (
    'Test User',
    'test@test.com',
    '1234567890',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    NOW()
);
```

## Files Modified

1. web/pages/login.php - Added error handling
2. web/pages/register.php - Added error handling  
3. public/api-test.html - NEW (API tester)
4. public/debug-routes.php - NEW (Route debugger)

## Next Steps

1. Test API directly using api-test.html
2. If API works, issue is in frontend
3. If API doesn't work, issue is in backend routing
4. Check browser console for exact error messages
