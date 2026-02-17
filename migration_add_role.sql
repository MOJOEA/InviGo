-- Add role column to Users table for admin system
-- role: 0 = normal user, 1 = admin

ALTER TABLE Users ADD COLUMN role TINYINT DEFAULT 0;

-- Update first user (organizer@test.com) to be admin
-- (สมมติว่า user ID 1 คือ organizer)
UPDATE Users SET role = 1 WHERE email = 'organizer@test.com';
