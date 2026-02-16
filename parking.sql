zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zone_name VARCHAR(50)
)
parking_spots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spot_number VARCHAR(10),
    zone_id INT,
    status ENUM('free', 'occupied') DEFAULT 'free',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (zone_id) REFERENCES zones(id)
)
admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
)
