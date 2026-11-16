CREATE DATABASE IF NOT EXISTS restaurant_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_booking;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(80) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('guest','admin') NOT NULL DEFAULT 'guest',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE halls (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 description VARCHAR(255) DEFAULT NULL,
 capacity INT NOT NULL DEFAULT 20,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `tables` (
 id INT AUTO_INCREMENT PRIMARY KEY,
 hall_id INT NOT NULL,
 table_number VARCHAR(20) NOT NULL,
 seats INT NOT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 UNIQUE KEY unique_table_per_hall (hall_id, table_number),
 CONSTRAINT fk_tables_hall FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE CASCADE
);

CREATE TABLE bookings (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT DEFAULT NULL,
 table_id INT NOT NULL,
 guest_name VARCHAR(120) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 guests INT NOT NULL,
 booking_date DATE NOT NULL,
 booking_time TIME NOT NULL,
 status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
 CONSTRAINT fk_bookings_table FOREIGN KEY (table_id) REFERENCES `tables`(id)
);
CREATE INDEX idx_booking_date_table ON bookings(booking_date, table_id, status);

-- Demo password for admin is: password
INSERT INTO users(username,password,role) VALUES
('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');

INSERT INTO halls(name,description,capacity) VALUES
('Толори асосӣ','Толори барҳаво барои оила ва дӯстону наздикон.',60),
('Толори VIP','Фазои ором барои вохӯриҳои махсус.',20),
('Айвон','Фазои кушод барои рӯзҳои офтобӣ.',30);

INSERT INTO `tables`(hall_id,table_number,seats) VALUES
(1,'01',2),(1,'02',4),(1,'03',4),(1,'04',6),(1,'05',8),
(2,'VIP-1',4),(2,'VIP-2',8),(3,'A-1',4),(3,'A-2',6);
