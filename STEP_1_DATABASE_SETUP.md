# STEP 1: Database Setup

## 🎯 What We're Doing

Setting up the complete database with 32 tables to support all features:
- User, Owner, Admin management
- Villa management with images, amenities, pricing
- Booking system with guests
- Payment & finance tracking
- Reviews & ratings
- CMS content
- Chat & support
- Notifications
- System settings

---

## 📋 Steps to Execute

### Option A: Using phpMyAdmin (Recommended for Beginners)

1. **Login to phpMyAdmin**
   - Go to your hosting control panel (cPanel)
   - Click on phpMyAdmin
   - Select database: `u200283558_villa`

2. **Import the SQL File**
   - Click on "Import" tab
   - Click "Choose File"
   - Select: `complete_system_schema.sql`
   - Click "Go" button at the bottom
   - Wait for success message

3. **Verify Installation**
   - Click on your database name on the left
   - You should see 32+ tables listed

---

### Option B: Using MySQL Command Line

1. **Upload the SQL file** to your server

2. **Run this command:**
   ```bash
   mysql -u u200283558_villa -p u200283558_villa < complete_system_schema.sql
   ```

3. **Enter password when prompted:** `Ansi@2023`

---

### Option C: Using a PHP Installer Script (Easiest)

I'll create a web-based installer for you in the next step.

---

## ✅ What Gets Created

### Tables (32 total)
- users, owners, admins
- villas, villa_images, villa_amenities, villa_pricing_rules, villa_availability, villa_house_rules
- bookings, booking_guests
- payments, payment_logs, owner_earnings, owner_payouts, admin_commissions, promo_codes, refunds
- reviews, review_reports
- homepage_banners, destinations, blogs, testimonials
- support_tickets, support_ticket_replies, chats, chat_messages
- notifications
- system_settings, email_templates, sms_templates, push_templates
- activity_logs, user_favorites

### Indexes
- Optimized indexes for fast queries on villas, bookings, payments, reviews

### Default Data
- System settings (app name, currency, tax rate, commission)
- Email templates (booking confirmation, approval, payout)
- SMS templates (booking reminders)

---

## 🔍 After Installation

You can verify by running this query in phpMyAdmin:
```sql
SELECT COUNT(*) as table_count FROM information_schema.tables
WHERE table_schema = 'u200283558_villa';
```

Should return approximately 40+ tables (existing + new)

---

## ⚠️ Important Notes

1. **Backup First**: This SQL is safe (uses `CREATE IF NOT EXISTS`), but always backup your database first
2. **No Data Loss**: Existing tables won't be affected
3. **Safe to Re-run**: You can run this SQL multiple times without issues

---

## 🚀 Next Step

Once database is ready, I'll build:
- **Step 2**: Admin Dashboard API endpoints
- **Step 3**: Admin Dashboard frontend

Ready to proceed? Let me know when the database is set up! ✅
