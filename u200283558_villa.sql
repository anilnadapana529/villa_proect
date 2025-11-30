-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 12:39 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u200283558_villa`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `actor_type` varchar(20) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `permissions` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `permissions`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@villa.com', '$2y$10$1GW9MQcMoxPf3oUI59CzqOFGnV38pHNXGw2z.Bbr0WBug5Drc4OU6', 'admin', NULL, 1, NULL, '2025-11-25 16:18:00', '2025-11-30 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `admin_commissions`
--

CREATE TABLE `admin_commissions` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(250) NOT NULL,
  `content` text DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `featured_image` text DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `is_published` tinyint(4) DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_number` varchar(50) DEFAULT NULL,
  `villa_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `nights` int(11) NOT NULL DEFAULT 1,
  `base_price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `promo_code` varchar(50) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `total_amount` int(11) DEFAULT NULL,
  `admin_commission` decimal(10,2) DEFAULT 0.00,
  `owner_earnings` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','cancelled','completed','rejected') DEFAULT 'pending',
  `payment_status` varchar(20) DEFAULT 'pending',
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_by` varchar(20) DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_blocks`
--

CREATE TABLE `booking_blocks` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_guests`
--

CREATE TABLE `booking_guests` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `id_proof` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_blocks`
--

CREATE TABLE `calendar_blocks` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `status`, `created_at`) VALUES
(1, 'Beach Villas', 'fa-solid fa-umbrella-beach', 'active', '2025-11-30 04:17:37'),
(2, 'Mountain Villas', 'fa-solid fa-mountain', 'active', '2025-11-30 04:17:37'),
(3, 'City Luxury', 'fa-solid fa-city', 'active', '2025-11-30 04:17:37'),
(4, 'Private Pool Villas', 'fa-solid fa-water-ladder', 'active', '2025-11-30 04:17:37'),
(5, 'Budget Friendly', 'fa-solid fa-wallet', 'active', '2025-11-30 04:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `last_message` text DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_type` varchar(20) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT 'India',
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `villa_count` int(11) DEFAULT 0,
  `is_featured` tinyint(4) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_key` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `variables` text DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_key`, `subject`, `body`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'booking_confirmation', 'Booking Confirmation - {{booking_number}}', 'Hello {{user_name}},\n\nYour booking has been confirmed!\n\nBooking Number: {{booking_number}}\nVilla: {{villa_name}}\nCheck-in: {{check_in}}\nCheck-out: {{check_out}}\n\nThank you!', 'user_name,booking_number,villa_name,check_in,check_out', 1, '2025-11-30 11:28:13', '2025-11-30 11:28:13'),
(2, 'booking_approved', 'Booking Approved - {{booking_number}}', 'Hello {{user_name}},\n\nYour booking has been approved!\n\nBooking Number: {{booking_number}}\n\nThank you!', 'user_name,booking_number', 1, '2025-11-30 11:28:13', '2025-11-30 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `recipient_type` varchar(20) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `data` text DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` text DEFAULT NULL,
  `id_proof` text DEFAULT NULL,
  `property_ownership_proof` text DEFAULT NULL,
  `verification_status` varchar(20) DEFAULT 'pending',
  `wallet_balance` decimal(10,2) DEFAULT 0.00,
  `total_earnings` decimal(10,2) DEFAULT 0.00,
  `commission_rate` decimal(5,2) DEFAULT 15.00,
  `is_active` tinyint(1) DEFAULT 1,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `name`, `email`, `password`, `phone`, `profile_image`, `id_proof`, `property_ownership_proof`, `verification_status`, `wallet_balance`, `total_earnings`, `commission_rate`, `is_active`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Anil', 'admin@villas.com', '$2y$10$GXP8P/hug1Z68BQ6uubQD.3xK/8p29u.BqRW/j.ZGn1UxF03hZHty', '9160153942', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 17:41:13', '2025-11-30 11:28:13'),
(2, 'Arjun Sharma', 'owner0@gmail.com', '$2y$10$8JyMVHgUWqRF1oda8QVSzOuKukFHlTggYZFGsNxpvY.GLUlZvakvC', '9876586146', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(3, 'Ravi Kumar', 'owner1@gmail.com', '$2y$10$k0aZu9z1iyvHppoxoHNM5.1powS0EbqNaFUJSfwkigecNQvCFykkS', '9876591393', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(4, 'Neha Singh', 'owner2@gmail.com', '$2y$10$cJwOM5zov9Hy4yJtLP6Uxuz3C1GqCh8S/Gsuab/.xhWosff9D17pa', '9876590061', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(5, 'Priya Patel', 'owner3@gmail.com', '$2y$10$W5DsF1bD5zCalLsRIC6RneMOcwQg1m.jzHkfnSPOnzmqaJb42CXXq', '9876533504', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(6, 'Akash Verma', 'owner4@gmail.com', '$2y$10$cU4lvXDXi7uewBt3m8Cf2uQCM38ME9HEjuYVBPMgD327wP6c4OPIa', '9876596273', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(7, 'Sanjay Rao', 'owner5@gmail.com', '$2y$10$G7pvrALoONR8e5B8Sk9NVuVtkiKO3HD3kqUzxkLuxnJdjWiGl5VdG', '9876522850', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(8, 'Aditi Mehta', 'owner6@gmail.com', '$2y$10$T1RcekYRuHWZj.xuw1tkwezxVDZSpRPFMdPlpcLPgdesdFlsQ6FRq', '9876585859', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(9, 'Rohan Das', 'owner7@gmail.com', '$2y$10$.TjW7rHvSzEw05zgFY2WGuzgYEPaLlljOHyxippW2XkR8uSr/.SSu', '9876523111', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(10, 'Kiran Reddy', 'owner8@gmail.com', '$2y$10$I3V3vMls03Sq3/jF3lOREuwKsJJTunLp0PvCcVXfwgo4xZi1DEHqC', '9876591469', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(11, 'Vikas Gupta', 'owner9@gmail.com', '$2y$10$m9UjFzOkCX9Uta7Gv4M4GOF7obTcrJi2tHQwoIs4.eBwzyAySl0y2', '9876536727', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(12, 'Megha Kapoor', 'owner10@gmail.com', '$2y$10$9xXNdq/8WfamQ12QDYLNvO6gaa8Bf4LaljMonMwiMiLGQkYMmlFna', '9876537622', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(13, 'Deepak Shah', 'owner11@gmail.com', '$2y$10$/v37gsQm/SnnjzEboRpzjO4MqK9s/8PKNQra0hsCQwM3JCI7cf7u6', '9876587504', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(14, 'Sneha Nair', 'owner12@gmail.com', '$2y$10$8U5Gmye0uiDSUFclpzmA/.aJXFrySAdZk5a32GqBOEbUCG8vHpsIu', '9876593514', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:29', '2025-11-30 11:28:13'),
(15, 'Harsh Goel', 'owner13@gmail.com', '$2y$10$vYt0Q50iaJTD1Mn7dDC7V.89I7HMCwNgAorVua8iep3AizHlMOPxa', '9876544694', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(16, 'Tanya Jain', 'owner14@gmail.com', '$2y$10$kW6stmiALmbfpjjlVJTj3.h7gu2raPDzB9A.uNhw7V1T5jKE2.H8q', '9876527717', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(17, 'Yash Malhotra', 'owner15@gmail.com', '$2y$10$ZcCnuv73DuxNyucZSGh4ee2Ph3DKBwgklVH6DRaNd0VG820TqPtC2', '9876585871', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(18, 'Nidhi Bansal', 'owner16@gmail.com', '$2y$10$JZQuFj4DJ97RfLueAxQ63OSU3S7mPAF4.YVveYmDUrFjsL/uvlslC', '9876554792', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(19, 'Varun Khanna', 'owner17@gmail.com', '$2y$10$M24aN4CI/GJ0dyph0FfUSuvRBzcHLqV/TDuAhf1GlMeeYYWKhrzwa', '9876596105', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(20, 'Rahul Mishra', 'owner18@gmail.com', '$2y$10$oP6sT9r4W9w6wtgfT9zQjOJCsBEn.21RhPx5dAp1Ja2ZsjLtBx1V2', '9876550817', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(21, 'Komal Arora', 'owner19@gmail.com', '$2y$10$xJcXHuhrikN23amJBxLX/OsWl81VLLNyTP5gb6C86d.ZJSdmwXDlC', '9876541575', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-25 18:05:30', '2025-11-30 11:28:13'),
(23, 'Sindhu', 'sindhu@gmail.com', '$2y$10$NsRGEwT6PaqwjSXkIXlDBeZz8VJ16xH4KFLx1VnkT1cK/hQBWm12u', '9160153942', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-28 07:01:20', '2025-11-30 11:28:13'),
(24, 'Rohit Sharma', 'rohit@villa.com', '5be057accb25758101fa5eadbbd79503', '9876543210', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-30 04:17:45', '2025-11-30 11:28:13'),
(25, 'Ananya Rao', 'ananya@villa.com', '5be057accb25758101fa5eadbbd79503', '9998877665', NULL, NULL, NULL, 'pending', 0.00, 0.00, 15.00, 1, 'approved', '2025-11-30 04:17:45', '2025-11-30 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `owner_earnings`
--

CREATE TABLE `owner_earnings` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) DEFAULT 0.00,
  `net_earnings` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner_payouts`
--

CREATE TABLE `owner_payouts` (
  `id` int(11) NOT NULL,
  `payout_number` varchar(50) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `account_details` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `requested_at` timestamp NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_number` varchar(50) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_gateway` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `log_type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `request_data` text DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `razor_key` varchar(200) DEFAULT NULL,
  `razor_secret` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `razor_key`, `razor_secret`) VALUES
(1, 'YOUR_KEY', 'YOUR_SECRET');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` varchar(20) DEFAULT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_booking_amount` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT 0,
  `used_count` int(11) DEFAULT 0,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_types`
--

CREATE TABLE `property_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_types`
--

INSERT INTO `property_types` (`id`, `name`, `icon`, `status`) VALUES
(1, 'Villa', '🏡', 'active'),
(2, 'Farmhouse', '🌾', 'active'),
(3, 'Beach House', '🏖️', 'active'),
(4, 'Pool Villa', '🏊', 'active'),
(5, 'Party Villa', '🍾', 'active'),
(6, 'Hill View Villa', '🏔️', 'active'),
(7, 'Forest Stay', '🌳', 'active'),
(8, 'Luxury Villa', '🏰', 'active'),
(9, 'Cottage', '🏕️', 'active'),
(10, 'Bungalow', '🏘️', 'active'),
(11, 'Guest House', '🛌', 'active'),
(12, 'Apartment', '🏙️', 'active'),
(13, 'Homestay', '🧳', 'active'),
(14, 'Family Home', '👨‍👩‍👧', 'active'),
(15, 'Large Group Stay', '👥', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `push_templates`
--

CREATE TABLE `push_templates` (
  `id` int(11) NOT NULL,
  `template_key` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `variables` text DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `refund_number` varchar(50) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `villa_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `photos` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `owner_response` text DEFAULT NULL,
  `is_reported` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_reports`
--

CREATE TABLE `review_reports` (
  `id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image`, `title`, `status`, `created_at`) VALUES
(1, 'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg', 'Luxury Villa With Pool', 'active', '2025-11-30 04:17:27'),
(2, 'https://images.pexels.com/photos/280229/pexels-photo-280229.jpeg', 'Modern Sea View Villa', 'active', '2025-11-30 04:17:27'),
(3, 'https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg', 'Premium Mountain Stay', 'active', '2025-11-30 04:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `id` int(11) NOT NULL,
  `template_key` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `variables` text DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_templates`
--

INSERT INTO `sms_templates` (`id`, `template_key`, `message`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'booking_confirmation', 'Your booking {{booking_number}} is confirmed. Check-in: {{check_in}}', 'booking_number,check_in', 1, '2025-11-30 11:28:13', '2025-11-30 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `priority` varchar(20) DEFAULT 'medium',
  `status` varchar(20) DEFAULT 'open',
  `assigned_to` int(11) DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_replies`
--

CREATE TABLE `support_ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `attachments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'app_name', 'Villa Booking System', 'string', 'Application name', '2025-11-30 11:28:13'),
(2, 'currency', 'INR', 'string', 'Default currency', '2025-11-30 11:28:13'),
(3, 'tax_percentage', '18', 'number', 'Tax percentage on bookings', '2025-11-30 11:28:13'),
(4, 'admin_commission', '15', 'number', 'Admin commission percentage', '2025-11-30 11:28:13'),
(5, 'session_timeout', '3600', 'number', 'Session timeout in seconds', '2025-11-30 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` text DEFAULT NULL,
  `id_proof` text DEFAULT NULL,
  `kyc_status` varchar(20) DEFAULT 'pending',
  `wallet_balance` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `profile_image`, `id_proof`, `kyc_status`, `wallet_balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kiran Kumar', 'kiran@user.com', '6ad14ba9986e3615423dfca256d04e3f', '9512345678', NULL, NULL, 'pending', 0.00, 'active', '2025-11-30 04:18:06', '2025-11-30 11:28:13'),
(2, 'Sneha Reddy', 'sneha@user.com', '6ad14ba9986e3615423dfca256d04e3f', '9871234560', NULL, NULL, 'pending', 0.00, 'active', '2025-11-30 04:18:06', '2025-11-30 11:28:13'),
(3, 'Anil', 'anil@gmail.com', '$2y$10$xIzvpaqcMd3lt2SJSWYspO4ClIkLhjd54FbotQis2/qMaelN5uh1W', '9160153942', NULL, NULL, 'pending', 0.00, 'active', '2025-11-30 12:26:34', '2025-11-30 12:26:34');

-- --------------------------------------------------------

--
-- Table structure for table `villas`
--

CREATE TABLE `villas` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `guests` int(11) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `beds` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `square_feet` int(11) DEFAULT NULL,
  `weekday_price` decimal(10,2) DEFAULT NULL,
  `weekend_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `featured` tinyint(4) DEFAULT 0,
  `trending` tinyint(4) DEFAULT 0,
  `verified` tinyint(4) DEFAULT 0,
  `instant_booking` tinyint(4) DEFAULT 0,
  `total_bookings` int(11) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `property_type_id` int(11) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `map_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `villas`
--

INSERT INTO `villas` (`id`, `owner_id`, `name`, `slug`, `location`, `address`, `description`, `amenities`, `guests`, `bedrooms`, `beds`, `bathrooms`, `price_per_night`, `square_feet`, `weekday_price`, `weekend_price`, `status`, `rejection_reason`, `featured`, `trending`, `verified`, `instant_booking`, `total_bookings`, `average_rating`, `total_reviews`, `created_at`, `updated_at`, `property_type_id`, `latitude`, `longitude`, `map_link`) VALUES
(1, 2, 'Mahabaleshwar Luxury Villa 35', NULL, 'Alibaug', 'Near beach road, Alibaug', 'Beautiful private villa located in Alibaug with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 5, 4, 4, 4, NULL, NULL, 5958.00, 8189.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(2, 3, 'Pondicherry Luxury Villa 62', NULL, 'Ooty', 'Near beach road, Ooty', 'Beautiful private villa located in Ooty with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 9, 5, 5, 2, NULL, NULL, 6172.00, 9145.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(3, 4, 'Coorg Luxury Villa 26', NULL, 'Goa', 'Near beach road, Goa', 'Beautiful private villa located in Goa with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 11, 3, 3, 4, NULL, NULL, 6311.00, 11044.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(4, 5, 'Pondicherry Luxury Villa 34', NULL, 'Manali', 'Near beach road, Manali', 'Beautiful private villa located in Manali with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 9, 5, 5, 2, NULL, NULL, 4005.00, 10650.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(5, 6, 'Mahabaleshwar Luxury Villa 76', NULL, 'Manali', 'Near beach road, Manali', 'Beautiful private villa located in Manali with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 6, 3, 3, 4, NULL, NULL, 6298.00, 12368.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(6, 7, 'Ooty Luxury Villa 97', NULL, 'Alibaug', 'Near beach road, Alibaug', 'Beautiful private villa located in Alibaug with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 12, 3, 3, 4, NULL, NULL, 8152.00, 11956.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(7, 8, 'Alibaug Luxury Villa 54', NULL, 'Pondicherry', 'Near beach road, Pondicherry', 'Beautiful private villa located in Pondicherry with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 9, 5, 5, 2, NULL, NULL, 6457.00, 8657.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(8, 9, 'Mahabaleshwar Luxury Villa 41', NULL, 'Pondicherry', 'Near beach road, Pondicherry', 'Beautiful private villa located in Pondicherry with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 8, 4, 4, 2, NULL, NULL, 4947.00, 11124.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(9, 10, 'Alibaug Luxury Villa 31', NULL, 'Mahabaleshwar', 'Near beach road, Mahabaleshwar', 'Beautiful private villa located in Mahabaleshwar with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 4, 5, 5, 4, NULL, NULL, 6249.00, 10229.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(10, 11, 'Lonavala Luxury Villa 14', NULL, 'Pondicherry', 'Near beach road, Pondicherry', 'Beautiful private villa located in Pondicherry with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 4, 2, 2, 4, NULL, NULL, 6422.00, 7413.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(11, 12, 'Mahabaleshwar Luxury Villa 13', NULL, 'Alibaug', 'Near beach road, Alibaug', 'Beautiful private villa located in Alibaug with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 9, 2, 2, 3, NULL, NULL, 6274.00, 11122.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(12, 13, 'Manali Luxury Villa 45', NULL, 'Coorg', 'Near beach road, Coorg', 'Beautiful private villa located in Coorg with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 4, 2, 2, 2, NULL, NULL, 5092.00, 12717.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(13, 14, 'Alibaug Luxury Villa 11', NULL, 'Lonavala', 'Near beach road, Lonavala', 'Beautiful private villa located in Lonavala with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 8, 3, 3, 3, NULL, NULL, 5832.00, 11620.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:29', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(14, 15, 'Lonavala Luxury Villa 31', NULL, 'Manali', 'Near beach road, Manali', 'Beautiful private villa located in Manali with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 5, 3, 3, 2, NULL, NULL, 7361.00, 12826.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(15, 16, 'Pondicherry Luxury Villa 30', NULL, 'Goa', 'Near beach road, Goa', 'Beautiful private villa located in Goa with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 6, 5, 5, 4, NULL, NULL, 7080.00, 8790.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(16, 17, 'Manali Luxury Villa 23', NULL, 'Alibaug', 'Near beach road, Alibaug', 'Beautiful private villa located in Alibaug with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 10, 4, 4, 4, NULL, NULL, 7658.00, 10024.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(17, 18, 'Mahabaleshwar Luxury Villa 3', NULL, 'Manali', 'Near beach road, Manali', 'Beautiful private villa located in Manali with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 11, 2, 2, 3, NULL, NULL, 8395.00, 10887.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(18, 19, 'Alibaug Luxury Villa 58', NULL, 'Ooty', 'Near beach road, Ooty', 'Beautiful private villa located in Ooty with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 4, 4, 4, 3, NULL, NULL, 4974.00, 8954.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(19, 20, 'Goa Luxury Villa 9', NULL, 'Lonavala', 'Near beach road, Lonavala', 'Beautiful private villa located in Lonavala with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 4, 4, 4, 2, NULL, NULL, 8898.00, 13893.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(20, 21, 'Ooty Luxury Villa 13', NULL, 'Pondicherry', 'Near beach road, Pondicherry', 'Beautiful private villa located in Pondicherry with premium facilities and great ambiance.', 'Pool,AC,WiFi,Parking,Kitchen', 12, 4, 4, 4, NULL, NULL, 6413.00, 12973.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-25 18:05:30', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(21, 23, 'Test VIlla Proect', NULL, 'Hyderabad', 'Tes', 'Test', 'Pool,AC,WiFi,Parking,Kitchen,Caretaker,Pet Friendly,Party Allowed', 10, 5, 5, 5, NULL, NULL, 15000.00, 20000.00, 'approved', NULL, 0, 0, 0, 0, 0, 0.00, 0, '2025-11-28 07:23:26', '2025-11-30 11:28:13', NULL, NULL, NULL, NULL),
(22, 1, 'Sunset Beach Villa', 'sunset-beach-villa', 'Goa, India', 'Baga Beach, Goa', 'Luxury beachfront villa with private infinity pool and ocean views.', '[\"Pool\",\"Wifi\",\"Private beach\",\"Breakfast included\"]', 6, 3, 4, 3, NULL, NULL, 15000.00, 18000.00, 'approved', NULL, 1, 1, 1, 0, 0, 0.00, 0, '2025-11-30 04:21:54', '2025-11-30 11:28:13', 1, '15.5580', '73.7510', NULL),
(23, 1, 'Mountain View Glass Villa', 'mountain-view-glass-villa', 'Manali, Himachal Pradesh', 'Old Manali Road', 'Glass villa with 270-degree mountain views, fireplace and jacuzzi.', '[\"Mountain View\",\"Jacuzzi\",\"Fireplace\",\"Wifi\"]', 4, 2, 2, 2, NULL, NULL, 18000.00, 22000.00, 'approved', NULL, 1, 0, 1, 0, 0, 0.00, 0, '2025-11-30 04:21:54', '2025-11-30 11:28:13', 2, '32.2432', '77.1892', NULL),
(24, 2, 'Royal Heritage Palace Villa', 'royal-heritage-palace-villa', 'Jaipur, Rajasthan', 'Amer Road, Jaipur', 'Heritage palace-style villa with private courtyard and royal décor.', '[\"King Beds\",\"Chef Available\",\"Garden\",\"Wifi\"]', 10, 5, 5, 4, NULL, NULL, 25000.00, 30000.00, 'approved', NULL, 0, 1, 1, 0, 0, 0.00, 0, '2025-11-30 04:21:54', '2025-11-30 11:28:13', 3, '26.9855', '75.8513', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `villa_availability`
--

CREATE TABLE `villa_availability` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_blocked` tinyint(4) DEFAULT 0,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `villa_house_rules`
--

CREATE TABLE `villa_house_rules` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `rule` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `villa_images`
--

CREATE TABLE `villa_images` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `villa_images`
--

INSERT INTO `villa_images` (`id`, `villa_id`, `image`) VALUES
(1, 1, 'placeholder1.jpg'),
(2, 1, 'placeholder2.jpg'),
(3, 1, 'placeholder3.jpg'),
(4, 2, 'placeholder1.jpg'),
(5, 2, 'placeholder2.jpg'),
(6, 2, 'placeholder3.jpg'),
(7, 3, 'placeholder1.jpg'),
(8, 3, 'placeholder2.jpg'),
(9, 3, 'placeholder3.jpg'),
(10, 4, 'placeholder1.jpg'),
(11, 4, 'placeholder2.jpg'),
(12, 4, 'placeholder3.jpg'),
(13, 5, 'placeholder1.jpg'),
(14, 5, 'placeholder2.jpg'),
(15, 5, 'placeholder3.jpg'),
(16, 6, 'placeholder1.jpg'),
(17, 6, 'placeholder2.jpg'),
(18, 6, 'placeholder3.jpg'),
(19, 7, 'placeholder1.jpg'),
(20, 7, 'placeholder2.jpg'),
(21, 7, 'placeholder3.jpg'),
(22, 8, 'placeholder1.jpg'),
(23, 8, 'placeholder2.jpg'),
(24, 8, 'placeholder3.jpg'),
(25, 9, 'placeholder1.jpg'),
(26, 9, 'placeholder2.jpg'),
(27, 9, 'placeholder3.jpg'),
(28, 10, 'placeholder1.jpg'),
(29, 10, 'placeholder2.jpg'),
(30, 10, 'placeholder3.jpg'),
(31, 11, 'placeholder1.jpg'),
(32, 11, 'placeholder2.jpg'),
(33, 11, 'placeholder3.jpg'),
(34, 12, 'placeholder1.jpg'),
(35, 12, 'placeholder2.jpg'),
(36, 12, 'placeholder3.jpg'),
(37, 13, 'placeholder1.jpg'),
(38, 13, 'placeholder2.jpg'),
(39, 13, 'placeholder3.jpg'),
(40, 14, 'placeholder1.jpg'),
(41, 14, 'placeholder2.jpg'),
(42, 14, 'placeholder3.jpg'),
(43, 15, 'placeholder1.jpg'),
(44, 15, 'placeholder2.jpg'),
(45, 15, 'placeholder3.jpg'),
(46, 16, 'placeholder1.jpg'),
(47, 16, 'placeholder2.jpg'),
(48, 16, 'placeholder3.jpg'),
(49, 17, 'placeholder1.jpg'),
(50, 17, 'placeholder2.jpg'),
(51, 17, 'placeholder3.jpg'),
(52, 18, 'placeholder1.jpg'),
(53, 18, 'placeholder2.jpg'),
(54, 18, 'placeholder3.jpg'),
(55, 19, 'placeholder1.jpg'),
(56, 19, 'placeholder2.jpg'),
(57, 19, 'placeholder3.jpg'),
(58, 20, 'placeholder1.jpg'),
(59, 20, 'placeholder2.jpg'),
(60, 20, 'placeholder3.jpg'),
(61, 21, '1764314606_5838.jpg'),
(62, 21, '1764314606_5072.jpg'),
(63, 21, '1764314606_9672.jpg'),
(64, 21, '1764314606_5790.jpg'),
(65, 21, '1764314606_9692.jpg'),
(66, 21, '1764314606_3417.jpg'),
(67, 21, '1764314606_4348.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `villa_pricing_rules`
--

CREATE TABLE `villa_pricing_rules` (
  `id` int(11) NOT NULL,
  `villa_id` int(11) NOT NULL,
  `rule_type` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `min_nights` int(11) DEFAULT 1,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `villa_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_commissions`
--
ALTER TABLE `admin_commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_number` (`booking_number`),
  ADD KEY `fk_book_villa` (`villa_id`),
  ADD KEY `idx_owner_id` (`owner_id`),
  ADD KEY `fk_booking_owner` (`owner_id`);

--
-- Indexes for table `booking_blocks`
--
ALTER TABLE `booking_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_blocks`
--
ALTER TABLE `calendar_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_key` (`template_key`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `owner_earnings`
--
ALTER TABLE `owner_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owner_payouts`
--
ALTER TABLE `owner_payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payout_number` (`payout_number`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_number` (`payment_number`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `property_types`
--
ALTER TABLE `property_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_templates`
--
ALTER TABLE `push_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_key` (`template_key`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `refund_number` (`refund_number`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_reports`
--
ALTER TABLE `review_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_key` (`template_key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`);

--
-- Indexes for table `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `villas`
--
ALTER TABLE `villas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_owner_id` (`owner_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `villa_availability`
--
ALTER TABLE `villa_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_villa_date` (`villa_id`,`date`);

--
-- Indexes for table `villa_house_rules`
--
ALTER TABLE `villa_house_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `villa_images`
--
ALTER TABLE `villa_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `villa_pricing_rules`
--
ALTER TABLE `villa_pricing_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_commissions`
--
ALTER TABLE `admin_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_blocks`
--
ALTER TABLE `booking_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_guests`
--
ALTER TABLE `booking_guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_blocks`
--
ALTER TABLE `calendar_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `owner_earnings`
--
ALTER TABLE `owner_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner_payouts`
--
ALTER TABLE `owner_payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_types`
--
ALTER TABLE `property_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `push_templates`
--
ALTER TABLE `push_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_reports`
--
ALTER TABLE `review_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `villas`
--
ALTER TABLE `villas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `villa_availability`
--
ALTER TABLE `villa_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `villa_house_rules`
--
ALTER TABLE `villa_house_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `villa_images`
--
ALTER TABLE `villa_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `villa_pricing_rules`
--
ALTER TABLE `villa_pricing_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_book_villa` FOREIGN KEY (`villa_id`) REFERENCES `villas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
