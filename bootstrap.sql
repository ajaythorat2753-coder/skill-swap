-- Create DB and tables
CREATE DATABASE IF NOT EXISTS skillswap CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE skillswap;

-- users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- skills
CREATE TABLE IF NOT EXISTS skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  skill_name VARCHAR(120) NOT NULL,
  skill_type ENUM('Teach','Learn') NOT NULL,
  category VARCHAR(80) DEFAULT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- exchanges
CREATE TABLE IF NOT EXISTS exchanges (
  id INT AUTO_INCREMENT PRIMARY KEY,
  requester_id INT NOT NULL,
  receiver_id INT NOT NULL,
  skill_id INT NOT NULL,
  status ENUM('Pending','Accepted','Rejected','Cancelled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Seed admin (password: admin123)
INSERT INTO users(name,email,password_hash,role) VALUES
('Admin','admin@skillswap.local','$2y$10$0C0iSy9z8oY6SQM5Yxo2EON5vA9b9JqV3m2Cw0bG4zU1rZA9m8b1S','admin')
ON DUPLICATE KEY UPDATE email=email;

-- Sample users
INSERT INTO users(name,email,password_hash) VALUES
('Aarav','aarav@example.com','$2y$10$E7sAz9kq2bHkX6C1v2V7deuM8hY2p2k3p4L5n6Q7r8S9t0U1v2W3y'),
('Anaya','anaya@example.com','$2y$10$E7sAz9kq2bHkX6C1v2V7deuM8hY2p2k3p4L5n6Q7r8S9t0U1v2W3y'),
('Vihaan','vihaan@example.com','$2y$10$E7sAz9kq2bHkX6C1v2V7deuM8hY2p2k3p4L5n6Q7r8S9t0U1v2W3y')
ON DUPLICATE KEY UPDATE email=email;

-- Sample skills (uses existing user ids 1..3; adjust if needed)
INSERT INTO skills (user_id, skill_name, skill_type, category, description) VALUES
(1,'Full Stack Web Development','Teach','Programming','HTML, CSS, JS, PHP & MySQL complete website building.'),
(2,'UI/UX Design Basics','Learn','Design','I want to learn wireframing, prototyping in Figma, and usability principles.'),
(3,'Photography Editing','Teach','Photography','Teach Lightroom & Photoshop editing workflow.'),
(1,'Spoken English Practice','Learn','Soft Skills','Looking for a partner to practice daily conversation.');

