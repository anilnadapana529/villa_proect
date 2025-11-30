-- Add missing owner_id column to bookings table
ALTER TABLE bookings
ADD COLUMN IF NOT EXISTS owner_id INT DEFAULT NULL AFTER villa_id,
ADD KEY IF NOT EXISTS fk_booking_owner (owner_id);

-- Add foreign key constraint
-- ALTER TABLE bookings
-- ADD CONSTRAINT fk_booking_owner FOREIGN KEY (owner_id) REFERENCES owners(id);
