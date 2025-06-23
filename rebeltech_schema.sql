-- Rebel Tech Database Schema
-- Run this SQL in phpMyAdmin or MySQL CLI

-- 1️⃣ Start XAMPP Services
-- Open XAMPP Control Panel.
-- Start Apache and MySQL.

-- 2️⃣ Create the Database
CREATE DATABASE rebeltech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rebeltech;

-- 3️⃣ Create Tables

-- Users Table
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `address` TEXT,
  `city` VARCHAR(50),
  `state` VARCHAR(50),
  `country` VARCHAR(50) DEFAULT 'Pakistan',
  `postal_code` VARCHAR(20),
  `is_admin` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  `account_status` ENUM('active', 'suspended', 'deleted') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  INDEX `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Table
CREATE TABLE `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255)
);

-- Cart Table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Sample Data for Categories
INSERT INTO `categories` (`name`, `slug`, `description`) VALUES
('Laptops', 'laptops', 'High-performance laptops for work and gaming'),
('Accessories', 'accessories', 'Computer accessories and peripherals');

-- 5️⃣ Backup & Maintenance
-- Backup:
-- mysqldump -u root -p rebeltech > rebeltech_backup.sql

-- Optimize:
OPTIMIZE TABLE products, users, orders;

-- Monitor:
SHOW PROCESSLIST;
SELECT table_name, ROUND(((data_length + index_length)/1024/1024),2) AS Size_MB 
FROM information_schema.TABLES
WHERE table_schema = 'rebeltech';
