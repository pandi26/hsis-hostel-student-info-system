CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE leave_requests (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    leave_type VARCHAR(50) NOT NULL,
    hour_wise VARCHAR(3) NOT NULL,
    half_day VARCHAR(3) NOT NULL,
    selected_hour VARCHAR(50),
    session VARCHAR(20),
    from_date DATE NOT NULL,
    from_time TIME NOT NULL,
    to_date DATE NOT NULL,
    to_time TIME NOT NULL,
    leave_reason TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE leave_requests ADD COLUMN status VARCHAR(20) DEFAULT 'Pending';

