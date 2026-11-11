-- Run this ONCE in phpMyAdmin for the existing Activity 1 database.
-- This adds the role field without deleting existing users or records.
USE signin_register;

ALTER TABLE users
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER password;

-- After creating/signing up your own account, replace YOUR_USERNAME below
-- with your actual username to grant that account administrator access.
-- Example:
-- UPDATE users SET role = 'admin' WHERE username = 'angelica';
