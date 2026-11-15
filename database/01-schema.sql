
-- =====================================================
-- Restaurant Table Reservation System
-- Database: restaurant_booking
-- DBMS: MySQL 8.0.16+
-- =====================================================

CREATE DATABASE IF NOT EXISTS restaurant_booking
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE restaurant_booking;

-- =====================================================
-- 1. USERS
-- =====================================================

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(30) DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,

    role ENUM('customer', 'employee', 'admin')
        NOT NULL DEFAULT 'customer',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- 2. DINING TABLES
-- =====================================================

CREATE TABLE dining_tables (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    table_number VARCHAR(20) NOT NULL UNIQUE,
    capacity INT UNSIGNED NOT NULL,
    location VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,

    status ENUM('active', 'inactive')
        NOT NULL DEFAULT 'active',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_dining_tables_capacity
        CHECK (capacity > 0)
) ENGINE=InnoDB;

-- =====================================================
-- 3. RESERVATIONS
-- =====================================================

CREATE TABLE reservations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NOT NULL,
    table_id INT UNSIGNED NOT NULL,

    reservation_start DATETIME NOT NULL,
    reservation_end DATETIME NOT NULL,

    guests_count INT UNSIGNED NOT NULL,

    status ENUM(
        'pending',
        'confirmed',
        'rejected',
        'cancelled',
        'completed'
    ) NOT NULL DEFAULT 'pending',

    comment TEXT DEFAULT NULL,

    reviewed_by INT UNSIGNED DEFAULT NULL,
    reviewed_at DATETIME DEFAULT NULL,
    rejection_reason TEXT DEFAULT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_reservation_guests
        CHECK (guests_count > 0),

    CONSTRAINT chk_reservation_dates
        CHECK (reservation_end > reservation_start),

    CONSTRAINT fk_reservations_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_reservations_table
        FOREIGN KEY (table_id)
        REFERENCES dining_tables(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_reservations_reviewer
        FOREIGN KEY (reviewed_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    INDEX idx_reservations_user (user_id),
    INDEX idx_reservations_table (table_id),
    INDEX idx_reservations_status (status),
    INDEX idx_reservations_start (reservation_start),
    INDEX idx_reservations_table_time (
        table_id,
        reservation_start,
        reservation_end
    )
) ENGINE=InnoDB;

-- =====================================================
-- END OF DATABASE SCHEMA
-- =====================================================
