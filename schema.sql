
-- Run these queries in MySQL
CREATE DATABASE IF NOT EXISTS oyunindir CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE oyunindir;

DROP TABLE IF EXISTS games;
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description MEDIUMTEXT,
    size VARCHAR(50),
    image_url VARCHAR(500),
    screenshots MEDIUMTEXT, -- comma-separated URLs
    download_links MEDIUMTEXT, -- JSON array of {name, url}
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
    id INT PRIMARY KEY,
    site_name VARCHAR(255),
    logo_url VARCHAR(500),
    navbar_links MEDIUMTEXT -- JSON array of {name, url}
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


