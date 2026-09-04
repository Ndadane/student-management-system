-- Student Management System — actual database schema
-- (3 tables total: user, teacher, admission — not the 8+ claimed
-- in earlier drafts of the README.)

CREATE DATABASE IF NOT EXISTS schoolproject;
USE schoolproject;

-- Holds both admins and students, distinguished by `usertype`.
CREATE TABLE IF NOT EXISTS user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email    VARCHAR(150) NOT NULL,
    phone    VARCHAR(20),
    usertype ENUM('admin', 'student') NOT NULL,
    password VARCHAR(255) NOT NULL  -- bcrypt hash (see password_hash())
);

CREATE TABLE IF NOT EXISTS teacher (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    description TEXT,
    image       VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS admission (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(150) NOT NULL,
    email   VARCHAR(150) NOT NULL,
    phone   VARCHAR(20),
    message TEXT
);

-- Seed one admin account so you can log in after a fresh install.
-- Password is "admin123" — CHANGE THIS after first login.
-- Hash generated with: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO user (username, email, phone, usertype, password) VALUES
('admin', 'admin@example.com', '0000000000', 'admin',
 '$2y$10$PY.jk4BZSPi0HMYKoyJM/uR9RP6OEuRqpPtdQ/lRHlBBio67F/9Zu');
