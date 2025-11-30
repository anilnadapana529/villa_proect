/*
  DATABASE MIGRATION - ADD MISSING TABLES & COLUMNS
  
  Run this in phpMyAdmin to add all missing features for the complete villa booking system.
*/

-- =====================================================
-- RENAME & UPDATE EXISTING TABLES
-- =====================================================

-- Rename admin to admins for consistency
RENAME TABLE `admin` TO `admins`;

-- Add missing columns to admins
ALTER TABLE `admins`
ADD COLUMN `role` VARCHAR(50) DEFAULT 'admin' AFTER `password`,
ADD COLUMN `permissions` TEXT AFTER `role`,
ADD COLUMN `is_active` BOOLEAN DEFAULT 1 AFTER `permissions`,
ADD COLUMN `last_login` TIMESTAMP NULL AFTER `is_active`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Add missing columns to owners
ALTER TABLE `owners`
ADD COLUMN `profile_image` TEXT AFTER `phone`,
ADD COLUMN `id_proof` TEXT AFTER `profile_image`,
ADD COLUMN `property_ownership_proof` TEXT AFTER `id_proof`,
ADD COLUMN `verification_status` VARCHAR(20) DEFAULT 'pending' AFTER `property_ownership_proof`,
ADD COLUMN `wallet_balance` DECIMAL(10,2) DEFAULT 0.00 AFTER `verification_status`,
ADD COLUMN `total_earnings` DECIMAL(10,2) DEFAULT 0.00 AFTER `wallet_balance`,
ADD COLUMN `commission_rate` DECIMAL(5,2) DEFAULT 15.00 AFTER `total_earnings`,
ADD COLUMN `is_active` BOOLEAN DEFAULT 1 AFTER `commission_rate`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Add missing columns to users
ALTER TABLE `users`
ADD COLUMN `profile_image` TEXT AFTER `phone`,
ADD COLUMN `id_proof` TEXT AFTER `profile_image`,
ADD COLUMN `kyc_status` VARCHAR(20) DEFAULT 'pending' AFTER `id_proof`,
ADD COLUMN `wallet_balance` DECIMAL(10,2) DEFAULT 0.00 AFTER `kyc_status`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Add missing columns to villas
ALTER TABLE `villas`
ADD COLUMN `price_per_night` DECIMAL(10,2) AFTER `bathrooms`,
ADD COLUMN `square_feet` INT AFTER `price_per_night`,
ADD COLUMN `instant_booking` TINYINT DEFAULT 0 AFTER `verified`,
ADD COLUMN `rejection_reason` TEXT AFTER `status`,
ADD COLUMN `total_bookings` INT DEFAULT 0 AFTER `instant_booking`,
ADD COLUMN `average_rating` DECIMAL(3,2) DEFAULT 0.00 AFTER `total_bookings`,
ADD COLUMN `total_reviews` INT DEFAULT 0 AFTER `average_rating`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Add missing columns to bookings
ALTER TABLE `bookings`
ADD COLUMN `booking_number` VARCHAR(50) UNIQUE AFTER `id`,
ADD COLUMN `guests` INT NOT NULL DEFAULT 1 AFTER `check_out`,
ADD COLUMN `nights` INT NOT NULL DEFAULT 1 AFTER `guests`,
ADD COLUMN `base_price` DECIMAL(10,2) AFTER `nights`,
ADD COLUMN `discount` DECIMAL(10,2) DEFAULT 0.00 AFTER `base_price`,
ADD COLUMN `promo_code` VARCHAR(50) AFTER `discount`,
ADD COLUMN `tax` DECIMAL(10,2) DEFAULT 0.00 AFTER `promo_code`,
ADD COLUMN `admin_commission` DECIMAL(10,2) DEFAULT 0.00 AFTER `total_amount`,
ADD COLUMN `owner_earnings` DECIMAL(10,2) DEFAULT 0.00 AFTER `admin_commission`,
ADD COLUMN `payment_status` VARCHAR(20) DEFAULT 'pending' AFTER `status`,
ADD COLUMN `cancellation_reason` TEXT AFTER `payment_status`,
ADD COLUMN `cancelled_by` VARCHAR(20) AFTER `cancellation_reason`,
ADD COLUMN `cancelled_at` TIMESTAMP NULL AFTER `cancelled_by`,
ADD COLUMN `notes` TEXT AFTER `cancelled_at`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Modify bookings status to include more options
ALTER TABLE `bookings`
MODIFY `status` ENUM('pending','confirmed','cancelled','completed','rejected') DEFAULT 'pending';

-- Add missing columns to payments
ALTER TABLE `payments`
ADD COLUMN `payment_number` VARCHAR(50) UNIQUE AFTER `id`,
ADD COLUMN `booking_id` INT AFTER `payment_number`,
ADD COLUMN `payment_method` VARCHAR(50) AFTER `amount`,
ADD COLUMN `payment_gateway` VARCHAR(50) AFTER `payment_method`,
ADD COLUMN `transaction_id` VARCHAR(100) AFTER `payment_gateway`,
ADD COLUMN `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `status`;

-- Add missing columns to reviews
ALTER TABLE `reviews`
ADD COLUMN `booking_id` INT AFTER `id`,
ADD COLUMN `photos` TEXT AFTER `review`,
ADD COLUMN `status` VARCHAR(20) DEFAULT 'pending' AFTER `photos`,
ADD COLUMN `rejection_reason` TEXT AFTER `status`,
ADD COLUMN `owner_response` TEXT AFTER `rejection_reason`,
ADD COLUMN `is_reported` TINYINT DEFAULT 0 AFTER `owner_response`,
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Update owner_payouts
ALTER TABLE `owner_payouts`
ADD COLUMN `payout_number` VARCHAR(50) UNIQUE AFTER `id`,
ADD COLUMN `payment_method` VARCHAR(50) AFTER `amount`,
ADD COLUMN `account_details` TEXT AFTER `payment_method`,
ADD COLUMN `transaction_id` VARCHAR(100) AFTER `account_details`,
ADD COLUMN `status` VARCHAR(20) DEFAULT 'pending' AFTER `transaction_id`,
ADD COLUMN `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `status`,
ADD COLUMN `processed_at` TIMESTAMP NULL AFTER `requested_at`;

-- =====================================================
-- CREATE NEW TABLES
-- =====================================================

-- Villa pricing rules
CREATE TABLE IF NOT EXISTS `villa_pricing_rules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT NOT NULL,
    `rule_type` VARCHAR(20),
    `start_date` DATE,
    `end_date` DATE,
    `price` DECIMAL(10,2) NOT NULL,
    `min_nights` INT DEFAULT 1,
    `discount_percentage` DECIMAL(5,2) DEFAULT 0.00,
    `is_active` TINYINT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Villa availability
CREATE TABLE IF NOT EXISTS `villa_availability` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `is_blocked` TINYINT DEFAULT 0,
    `reason` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_villa_date` (`villa_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Villa house rules
CREATE TABLE IF NOT EXISTS `villa_house_rules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `villa_id` INT NOT NULL,
    `rule` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Booking guests
CREATE TABLE IF NOT EXISTS `booking_guests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `age` INT,
    `id_proof` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment logs
CREATE TABLE IF NOT EXISTS `payment_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `payment_id` INT NOT NULL,
    `log_type` VARCHAR(50),
    `message` TEXT,
    `request_data` TEXT,
    `response_data` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Owner earnings
CREATE TABLE IF NOT EXISTS `owner_earnings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `owner_id` INT NOT NULL,
    `booking_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `commission` DECIMAL(10,2) DEFAULT 0.00,
    `net_earnings` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin commissions
CREATE TABLE IF NOT EXISTS `admin_commissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `percentage` DECIMAL(5,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promo codes
CREATE TABLE IF NOT EXISTS `promo_codes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) UNIQUE NOT NULL,
    `description` TEXT,
    `discount_type` VARCHAR(20),
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_booking_amount` DECIMAL(10,2) DEFAULT 0.00,
    `max_discount` DECIMAL(10,2),
    `usage_limit` INT DEFAULT 0,
    `used_count` INT DEFAULT 0,
    `valid_from` DATE,
    `valid_to` DATE,
    `is_active` TINYINT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Refunds
CREATE TABLE IF NOT EXISTS `refunds` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `refund_number` VARCHAR(50) UNIQUE NOT NULL,
    `booking_id` INT NOT NULL,
    `payment_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `reason` TEXT,
    `status` VARCHAR(20) DEFAULT 'pending',
    `processed_by` INT,
    `processed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Review reports
CREATE TABLE IF NOT EXISTS `review_reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `review_id` INT NOT NULL,
    `reported_by` INT,
    `reason` TEXT,
    `status` VARCHAR(20) DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Destinations
CREATE TABLE IF NOT EXISTS `destinations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `country` VARCHAR(100) DEFAULT 'India',
    `description` TEXT,
    `image_url` TEXT,
    `villa_count` INT DEFAULT 0,
    `is_featured` TINYINT DEFAULT 0,
    `display_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blogs
CREATE TABLE IF NOT EXISTS `blogs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(250) UNIQUE NOT NULL,
    `content` TEXT,
    `excerpt` TEXT,
    `featured_image` TEXT,
    `author_id` INT,
    `category` VARCHAR(100),
    `tags` TEXT,
    `views` INT DEFAULT 0,
    `is_published` TINYINT DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Support tickets
CREATE TABLE IF NOT EXISTS `support_tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` INT,
    `owner_id` INT,
    `user_type` VARCHAR(20),
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `category` VARCHAR(50),
    `priority` VARCHAR(20) DEFAULT 'medium',
    `status` VARCHAR(20) DEFAULT 'open',
    `assigned_to` INT,
    `resolved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Support ticket replies
CREATE TABLE IF NOT EXISTS `support_ticket_replies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `user_id` INT,
    `admin_id` INT,
    `message` TEXT NOT NULL,
    `attachments` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chats
CREATE TABLE IF NOT EXISTS `chats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT,
    `user_id` INT,
    `owner_id` INT,
    `last_message` TEXT,
    `last_message_at` TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `chat_id` INT NOT NULL,
    `sender_type` VARCHAR(20),
    `sender_id` INT,
    `message` TEXT NOT NULL,
    `is_read` TINYINT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `owner_id` INT,
    `admin_id` INT,
    `recipient_type` VARCHAR(20),
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `data` TEXT,
    `is_read` TINYINT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System settings
CREATE TABLE IF NOT EXISTS `system_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT,
    `setting_type` VARCHAR(50),
    `description` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email templates
CREATE TABLE IF NOT EXISTS `email_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `template_key` VARCHAR(100) UNIQUE NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `body` TEXT NOT NULL,
    `variables` TEXT,
    `is_active` TINYINT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SMS templates
CREATE TABLE IF NOT EXISTS `sms_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `template_key` VARCHAR(100) UNIQUE NOT NULL,
    `message` TEXT NOT NULL,
    `variables` TEXT,
    `is_active` TINYINT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Push notification templates
CREATE TABLE IF NOT EXISTS `push_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `template_key` VARCHAR(100) UNIQUE NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `body` TEXT NOT NULL,
    `variables` TEXT,
    `is_active` TINYINT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `owner_id` INT,
    `admin_id` INT,
    `actor_type` VARCHAR(20),
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50),
    `entity_id` INT,
    `ip_address` VARCHAR(50),
    `user_agent` TEXT,
    `details` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- System settings
INSERT IGNORE INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('app_name', 'Villa Booking System', 'string', 'Application name'),
('currency', 'INR', 'string', 'Default currency'),
('tax_percentage', '18', 'number', 'Tax percentage on bookings'),
('admin_commission', '15', 'number', 'Admin commission percentage'),
('session_timeout', '3600', 'number', 'Session timeout in seconds');

-- Email templates
INSERT IGNORE INTO `email_templates` (`template_key`, `subject`, `body`, `variables`) VALUES
('booking_confirmation', 'Booking Confirmation - {{booking_number}}',
'Hello {{user_name}},\n\nYour booking has been confirmed!\n\nBooking Number: {{booking_number}}\nVilla: {{villa_name}}\nCheck-in: {{check_in}}\nCheck-out: {{check_out}}\n\nThank you!',
'user_name,booking_number,villa_name,check_in,check_out'),

('booking_approved', 'Booking Approved - {{booking_number}}',
'Hello {{user_name}},\n\nYour booking has been approved!\n\nBooking Number: {{booking_number}}\n\nThank you!',
'user_name,booking_number');

-- SMS templates
INSERT IGNORE INTO `sms_templates` (`template_key`, `message`, `variables`) VALUES
('booking_confirmation', 'Your booking {{booking_number}} is confirmed. Check-in: {{check_in}}',
'booking_number,check_in');
