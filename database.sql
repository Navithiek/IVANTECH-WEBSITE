-- IVANTECH SQL schema (MySQL / MariaDB)
-- Create database and tables. Edit credentials or use import tool.

CREATE DATABASE IF NOT EXISTS ivantech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ivantech;

-- Users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(191) NOT NULL,
  role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
  phone VARCHAR(50) DEFAULT '',
  address TEXT,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  description TEXT,
  status ENUM('active','inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  model VARCHAR(191) DEFAULT '',
  category_id INT DEFAULT NULL,
  description TEXT,
  specs TEXT,
  price DECIMAL(12,2) DEFAULT 0,
  badge VARCHAR(64) DEFAULT NULL,
  badge_color VARCHAR(12) DEFAULT NULL,
  featured TINYINT(1) DEFAULT 0,
  stock INT DEFAULT 0,
  status ENUM('active','inactive','out_of_stock') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Product images
CREATE TABLE product_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  path VARCHAR(255) NOT NULL,
  is_featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Inquiries
CREATE TABLE inquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inquiry_code VARCHAR(32) NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  message TEXT,
  status ENUM('pending','reviewing','quoted','completed','cancelled') DEFAULT 'pending',
  quoted_price DECIMAL(12,2) DEFAULT NULL,
  admin_notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Inquiry products (many-to-many)
CREATE TABLE inquiry_products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inquiry_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  product_name VARCHAR(191) DEFAULT '',
  FOREIGN KEY (inquiry_id) REFERENCES inquiries(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Employees
CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  position VARCHAR(191) DEFAULT '',
  department VARCHAR(191) DEFAULT '',
  email VARCHAR(191) DEFAULT '',
  phone VARCHAR(50) DEFAULT '',
  bio TEXT,
  status ENUM('active','inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Announcements
CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(191) NOT NULL,
  content TEXT,
  type ENUM('announcement','promotion') DEFAULT 'announcement',
  published TINYINT(1) DEFAULT 0,
  featured TINYINT(1) DEFAULT 0,
  start_date DATE DEFAULT NULL,
  end_date DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notifications
CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message TEXT,
  type VARCHAR(50) DEFAULT 'info',
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity logs
CREATE TABLE activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_name VARCHAR(191) NOT NULL,
  action VARCHAR(191) NOT NULL,
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Saved products (favorites)
CREATE TABLE saved_products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY ux_saved_user_product (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Password resets (optional)
CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(191) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed placeholder notes:
-- For security we recommend creating admin and demo customer accounts using a PHP seed script
-- that calls password_hash() to generate secure password hashes. See scripts/seed.php.
