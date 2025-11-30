-- Database Schema Fixes
-- Run this SQL in phpMyAdmin to fix all database issues

-- 1. Add missing owner_id column to bookings table
ALTER TABLE `bookings`
ADD COLUMN `owner_id` INT DEFAULT NULL AFTER `villa_id`;

-- 2. Add index for better query performance
ALTER TABLE `bookings`
ADD KEY `idx_owner_id` (`owner_id`);

-- 3. Add index on villa owner_id for better joins
ALTER TABLE `villas`
ADD KEY `idx_owner_id` (`owner_id`);

-- 4. Add index on villa status for faster filtering
ALTER TABLE `villas`
ADD KEY `idx_status` (`status`);

-- 5. Add index on owner status
ALTER TABLE `owners`
ADD KEY `idx_status` (`status`);

-- Note: If any of these columns/indexes already exist,
-- you may see warnings but the script will continue.
