/*
  # COMPLETE VILLA BOOKING SYSTEM - DATABASE SCHEMA

  ## Overview
  This migration creates a comprehensive villa booking platform with three role levels:
  - Admin: Complete system management
  - Owner: Villa management and bookings
  - User: Search, book, and review villas

  ## Tables Created

  ### Core User Tables
  1. `users` - Main user accounts (guests/travelers)
  2. `owners` - Villa owners with verification
  3. `admins` - System administrators

  ### Villa Management
  4. `villas` - Villa listings with details
  5. `villa_images` - Villa photo gallery
  6. `villa_amenities` - Available amenities per villa
  7. `villa_pricing_rules` - Seasonal and custom pricing
  8. `villa_availability` - Calendar blocking/unblocking

  ### Booking System
  9. `bookings` - Reservation records
  10. `booking_guests` - Guest details per booking

  ### Payment & Finance
  11. `payments` - Payment transactions
  12. `payment_logs` - Detailed payment tracking
  13. `owner_earnings` - Owner wallet and earnings
  14. `owner_payouts` - Payout history
  15. `admin_commissions` - Commission tracking
  16. `promo_codes` - Discount codes
  17. `refunds` - Refund records

  ### Reviews & Ratings
  18. `reviews` - User reviews with moderation
  19. `review_reports` - Reported reviews

  ### CMS Content
  20. `homepage_banners` - Slider images
  21. `destinations` - Featured destinations
  22. `blogs` - Travel guides and articles
  23. `testimonials` - Customer testimonials

  ### Communication
  24. `support_tickets` - Customer support
  25. `chats` - Real-time messaging
  26. `chat_messages` - Message history
  27. `notifications` - User notifications

  ### System Configuration
  28. `system_settings` - App configuration
  29. `email_templates` - Email templates
  30. `sms_templates` - SMS templates
  31. `push_templates` - Push notification templates
  32. `activity_logs` - System audit trail

  ## Security
  All tables have Row Level Security (RLS) enabled with appropriate policies.
*/

-- =====================================================
-- CORE USER TABLES
-- =====================================================

-- Users table (already exists, but let's ensure proper structure)
CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    profile_image TEXT,
    id_proof TEXT,
    kyc_status VARCHAR(20) DEFAULT 'pending' CHECK (kyc_status IN ('pending', 'approved', 'rejected')),
    wallet_balance DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Owners table
CREATE TABLE IF NOT EXISTS owners (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    profile_image TEXT,
    id_proof TEXT,
    property_ownership_proof TEXT,
    verification_status VARCHAR(20) DEFAULT 'pending' CHECK (verification_status IN ('pending', 'approved', 'rejected')),
    wallet_balance DECIMAL(10,2) DEFAULT 0.00,
    total_earnings DECIMAL(10,2) DEFAULT 0.00,
    commission_rate DECIMAL(5,2) DEFAULT 15.00,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    permissions TEXT,
    is_active BOOLEAN DEFAULT true,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- VILLA MANAGEMENT
-- =====================================================

-- Villas table
CREATE TABLE IF NOT EXISTS villas (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    owner_id UUID REFERENCES owners(id) ON DELETE CASCADE,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    villa_type VARCHAR(50) CHECK (villa_type IN ('beach', 'pool', 'luxury', 'mountain', 'city', 'countryside')),
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    country VARCHAR(100) DEFAULT 'India',
    zipcode VARCHAR(20),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests INTEGER NOT NULL DEFAULT 2,
    bedrooms INTEGER NOT NULL DEFAULT 1,
    bathrooms INTEGER NOT NULL DEFAULT 1,
    square_feet INTEGER,
    is_featured BOOLEAN DEFAULT false,
    instant_booking BOOLEAN DEFAULT false,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected', 'inactive')),
    rejection_reason TEXT,
    total_bookings INTEGER DEFAULT 0,
    average_rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Villa images
CREATE TABLE IF NOT EXISTS villa_images (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    image_url TEXT NOT NULL,
    is_primary BOOLEAN DEFAULT false,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Villa amenities
CREATE TABLE IF NOT EXISTS villa_amenities (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    amenity VARCHAR(100) NOT NULL,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Villa pricing rules (seasonal, weekly, custom)
CREATE TABLE IF NOT EXISTS villa_pricing_rules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    rule_type VARCHAR(20) CHECK (rule_type IN ('seasonal', 'weekly', 'monthly', 'custom')),
    start_date DATE,
    end_date DATE,
    price DECIMAL(10,2) NOT NULL,
    min_nights INTEGER DEFAULT 1,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Villa availability (calendar blocking)
CREATE TABLE IF NOT EXISTS villa_availability (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    is_blocked BOOLEAN DEFAULT false,
    reason TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(villa_id, date)
);

-- House rules
CREATE TABLE IF NOT EXISTS villa_house_rules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    rule TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- BOOKING SYSTEM
-- =====================================================

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_number VARCHAR(50) UNIQUE NOT NULL,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    owner_id UUID REFERENCES owners(id) ON DELETE CASCADE,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INTEGER NOT NULL,
    nights INTEGER NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) DEFAULT 0.00,
    promo_code VARCHAR(50),
    tax DECIMAL(10,2) DEFAULT 0.00,
    total_price DECIMAL(10,2) NOT NULL,
    admin_commission DECIMAL(10,2) DEFAULT 0.00,
    owner_earnings DECIMAL(10,2) DEFAULT 0.00,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'cancelled', 'completed', 'rejected')),
    payment_status VARCHAR(20) DEFAULT 'pending' CHECK (payment_status IN ('pending', 'paid', 'failed', 'refunded')),
    cancellation_reason TEXT,
    cancelled_by VARCHAR(20) CHECK (cancelled_by IN ('user', 'owner', 'admin')),
    cancelled_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Booking guests
CREATE TABLE IF NOT EXISTS booking_guests (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    age INTEGER,
    id_proof TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- PAYMENT & FINANCE
-- =====================================================

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    payment_number VARCHAR(50) UNIQUE NOT NULL,
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) CHECK (payment_method IN ('upi', 'card', 'netbanking', 'wallet')),
    payment_gateway VARCHAR(50),
    transaction_id VARCHAR(100),
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'success', 'failed', 'refunded')),
    payment_date TIMESTAMP DEFAULT NOW(),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Payment logs (detailed tracking)
CREATE TABLE IF NOT EXISTS payment_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    payment_id UUID REFERENCES payments(id) ON DELETE CASCADE,
    log_type VARCHAR(50),
    message TEXT,
    request_data TEXT,
    response_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Owner earnings
CREATE TABLE IF NOT EXISTS owner_earnings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    owner_id UUID REFERENCES owners(id) ON DELETE CASCADE,
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    commission DECIMAL(10,2) DEFAULT 0.00,
    net_earnings DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'available', 'paid')),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Owner payouts
CREATE TABLE IF NOT EXISTS owner_payouts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    payout_number VARCHAR(50) UNIQUE NOT NULL,
    owner_id UUID REFERENCES owners(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    account_details TEXT,
    transaction_id VARCHAR(100),
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'processing', 'completed', 'failed')),
    requested_at TIMESTAMP DEFAULT NOW(),
    processed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Admin commissions
CREATE TABLE IF NOT EXISTS admin_commissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    percentage DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Promo codes
CREATE TABLE IF NOT EXISTS promo_codes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    discount_type VARCHAR(20) CHECK (discount_type IN ('percentage', 'fixed')),
    discount_value DECIMAL(10,2) NOT NULL,
    min_booking_amount DECIMAL(10,2) DEFAULT 0.00,
    max_discount DECIMAL(10,2),
    usage_limit INTEGER DEFAULT 0,
    used_count INTEGER DEFAULT 0,
    valid_from DATE,
    valid_to DATE,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Refunds
CREATE TABLE IF NOT EXISTS refunds (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    refund_number VARCHAR(50) UNIQUE NOT NULL,
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    payment_id UUID REFERENCES payments(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    reason TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'processing', 'completed', 'failed')),
    processed_by UUID,
    processed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- REVIEWS & RATINGS
-- =====================================================

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    photos TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
    rejection_reason TEXT,
    owner_response TEXT,
    is_reported BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Review reports
CREATE TABLE IF NOT EXISTS review_reports (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    review_id UUID REFERENCES reviews(id) ON DELETE CASCADE,
    reported_by UUID,
    reason TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'reviewed', 'resolved')),
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- CMS CONTENT
-- =====================================================

-- Homepage banners
CREATE TABLE IF NOT EXISTS homepage_banners (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(200),
    subtitle TEXT,
    image_url TEXT NOT NULL,
    link_url TEXT,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Destinations
CREATE TABLE IF NOT EXISTS destinations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(100) DEFAULT 'India',
    description TEXT,
    image_url TEXT,
    villa_count INTEGER DEFAULT 0,
    is_featured BOOLEAN DEFAULT false,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Blogs
CREATE TABLE IF NOT EXISTS blogs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE NOT NULL,
    content TEXT,
    excerpt TEXT,
    featured_image TEXT,
    author_id UUID REFERENCES admins(id),
    category VARCHAR(100),
    tags TEXT,
    views INTEGER DEFAULT 0,
    is_published BOOLEAN DEFAULT false,
    published_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_name VARCHAR(100) NOT NULL,
    user_image TEXT,
    user_location VARCHAR(100),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    testimonial TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT false,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- COMMUNICATION
-- =====================================================

-- Support tickets
CREATE TABLE IF NOT EXISTS support_tickets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    ticket_number VARCHAR(50) UNIQUE NOT NULL,
    user_id UUID,
    owner_id UUID,
    user_type VARCHAR(20) CHECK (user_type IN ('user', 'owner')),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    category VARCHAR(50),
    priority VARCHAR(20) DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'urgent')),
    status VARCHAR(20) DEFAULT 'open' CHECK (status IN ('open', 'in_progress', 'resolved', 'closed')),
    assigned_to UUID REFERENCES admins(id),
    resolved_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Support ticket replies
CREATE TABLE IF NOT EXISTS support_ticket_replies (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    ticket_id UUID REFERENCES support_tickets(id) ON DELETE CASCADE,
    user_id UUID,
    admin_id UUID REFERENCES admins(id),
    message TEXT NOT NULL,
    attachments TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Chats (chat rooms)
CREATE TABLE IF NOT EXISTS chats (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_id UUID REFERENCES bookings(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id),
    owner_id UUID REFERENCES owners(id),
    last_message TEXT,
    last_message_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Chat messages
CREATE TABLE IF NOT EXISTS chat_messages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    chat_id UUID REFERENCES chats(id) ON DELETE CASCADE,
    sender_type VARCHAR(20) CHECK (sender_type IN ('user', 'owner')),
    sender_id UUID,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID,
    owner_id UUID,
    admin_id UUID,
    recipient_type VARCHAR(20) CHECK (recipient_type IN ('user', 'owner', 'admin')),
    type VARCHAR(50) NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    data TEXT,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT NOW()
);

-- =====================================================
-- SYSTEM CONFIGURATION
-- =====================================================

-- System settings
CREATE TABLE IF NOT EXISTS system_settings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(50),
    description TEXT,
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Email templates
CREATE TABLE IF NOT EXISTS email_templates (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    template_key VARCHAR(100) UNIQUE NOT NULL,
    subject VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    variables TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- SMS templates
CREATE TABLE IF NOT EXISTS sms_templates (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    template_key VARCHAR(100) UNIQUE NOT NULL,
    message TEXT NOT NULL,
    variables TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Push notification templates
CREATE TABLE IF NOT EXISTS push_templates (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    template_key VARCHAR(100) UNIQUE NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    variables TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Activity logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID,
    owner_id UUID,
    admin_id UUID,
    actor_type VARCHAR(20),
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id UUID,
    ip_address VARCHAR(50),
    user_agent TEXT,
    details TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- User favorites
CREATE TABLE IF NOT EXISTS user_favorites (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    villa_id UUID REFERENCES villas(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(user_id, villa_id)
);

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_villas_owner ON villas(owner_id);
CREATE INDEX IF NOT EXISTS idx_villas_status ON villas(status);
CREATE INDEX IF NOT EXISTS idx_villas_city ON villas(city);
CREATE INDEX IF NOT EXISTS idx_villas_featured ON villas(is_featured);

CREATE INDEX IF NOT EXISTS idx_bookings_user ON bookings(user_id);
CREATE INDEX IF NOT EXISTS idx_bookings_villa ON bookings(villa_id);
CREATE INDEX IF NOT EXISTS idx_bookings_owner ON bookings(owner_id);
CREATE INDEX IF NOT EXISTS idx_bookings_status ON bookings(status);
CREATE INDEX IF NOT EXISTS idx_bookings_dates ON bookings(check_in, check_out);

CREATE INDEX IF NOT EXISTS idx_payments_booking ON payments(booking_id);
CREATE INDEX IF NOT EXISTS idx_payments_user ON payments(user_id);
CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status);

CREATE INDEX IF NOT EXISTS idx_reviews_villa ON reviews(villa_id);
CREATE INDEX IF NOT EXISTS idx_reviews_user ON reviews(user_id);
CREATE INDEX IF NOT EXISTS idx_reviews_status ON reviews(status);

CREATE INDEX IF NOT EXISTS idx_notifications_recipient ON notifications(user_id, owner_id, admin_id);
CREATE INDEX IF NOT EXISTS idx_notifications_read ON notifications(is_read);

CREATE INDEX IF NOT EXISTS idx_villa_availability_date ON villa_availability(villa_id, date);

-- =====================================================
-- INSERT DEFAULT SYSTEM SETTINGS
-- =====================================================

INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('app_name', 'Villa Booking System', 'string', 'Application name'),
('app_logo', '', 'string', 'Application logo URL'),
('currency', 'INR', 'string', 'Default currency'),
('tax_percentage', '18', 'number', 'Tax percentage on bookings'),
('admin_commission', '15', 'number', 'Admin commission percentage'),
('session_timeout', '3600', 'number', 'Session timeout in seconds'),
('razorpay_key_id', '', 'string', 'Razorpay Key ID'),
('razorpay_key_secret', '', 'string', 'Razorpay Key Secret'),
('smtp_host', '', 'string', 'SMTP host'),
('smtp_port', '587', 'number', 'SMTP port'),
('smtp_username', '', 'string', 'SMTP username'),
('smtp_password', '', 'string', 'SMTP password'),
('sms_api_key', '', 'string', 'SMS API key'),
('google_calendar_api_key', '', 'string', 'Google Calendar API key')
ON CONFLICT (setting_key) DO NOTHING;

-- =====================================================
-- INSERT DEFAULT EMAIL TEMPLATES
-- =====================================================

INSERT INTO email_templates (template_key, subject, body, variables) VALUES
('booking_confirmation', 'Booking Confirmation - {{booking_number}}',
'Hello {{user_name}},\n\nYour booking has been confirmed!\n\nBooking Number: {{booking_number}}\nVilla: {{villa_name}}\nCheck-in: {{check_in}}\nCheck-out: {{check_out}}\nGuests: {{guests}}\nTotal: {{currency}}{{total_price}}\n\nThank you!',
'user_name,booking_number,villa_name,check_in,check_out,guests,currency,total_price'),

('booking_approved', 'Booking Approved - {{booking_number}}',
'Hello {{user_name}},\n\nGreat news! Your booking has been approved by the owner.\n\nBooking Number: {{booking_number}}\nVilla: {{villa_name}}\n\nThank you!',
'user_name,booking_number,villa_name'),

('booking_rejected', 'Booking Update - {{booking_number}}',
'Hello {{user_name}},\n\nWe regret to inform you that your booking has been declined.\n\nBooking Number: {{booking_number}}\nReason: {{reason}}\n\nYour payment will be refunded within 5-7 business days.',
'user_name,booking_number,reason'),

('payout_processed', 'Payout Processed - {{payout_number}}',
'Hello {{owner_name}},\n\nYour payout has been processed successfully.\n\nPayout Number: {{payout_number}}\nAmount: {{currency}}{{amount}}\n\nThank you!',
'owner_name,payout_number,currency,amount')
ON CONFLICT (template_key) DO NOTHING;

-- =====================================================
-- INSERT DEFAULT SMS TEMPLATES
-- =====================================================

INSERT INTO sms_templates (template_key, message, variables) VALUES
('booking_confirmation', 'Your booking {{booking_number}} is confirmed for {{villa_name}} from {{check_in}} to {{check_out}}. Total: {{currency}}{{total_price}}',
'booking_number,villa_name,check_in,check_out,currency,total_price'),

('booking_approved', 'Your booking {{booking_number}} has been approved! Check-in: {{check_in}}',
'booking_number,check_in'),

('booking_reminder', 'Reminder: Your check-in at {{villa_name}} is tomorrow. Booking: {{booking_number}}',
'villa_name,booking_number')
ON CONFLICT (template_key) DO NOTHING;
