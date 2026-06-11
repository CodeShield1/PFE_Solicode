CREATE DATABASE constructrent;
USE constructrent;


CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    image VARCHAR(255) DEFAULT NULL
);




CREATE TABLE cities (
    id_city INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    image VARCHAR(255) DEFAULT NULL
);



CREATE TABLE equipment (
    id_equipment INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price_per_day DECIMAL(10,2) NOT NULL,
    quantity_stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    category_id INT NOT NULL,
    city_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id)
        REFERENCES categories(id_category)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    FOREIGN KEY (city_id)
        REFERENCES cities(id_city)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);






CREATE TABLE reservations (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    start_date DATE NOT NULL,
    end_date DATE NOT NULL,

    -- site_address VARCHAR(255) NOT NULL,

    total_price DECIMAL(12,2) NOT NULL DEFAULT 0,

    status ENUM(
        'Pending',
        'Approved',
        'Rejected'
    ) DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE reservation_equipment (

    reservation_id INT NOT NULL,
    equipment_id INT NOT NULL,

    quantity INT NOT NULL DEFAULT 1,

    PRIMARY KEY (
        reservation_id,
        equipment_id
    ),

    FOREIGN KEY (reservation_id)
        REFERENCES reservations(id_reservation)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (equipment_id)
        REFERENCES equipment(id_equipment)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- INSERT INTO users
-- (
--     name,
--     email,
--     phone,
--     password,
--     role
-- )
-- VALUES
-- (
--     'Administrator',
--     'admin@constructrent.com',
--     '0600000000',
--     '$2y$10$HASH_PASSWORD_HERE',
--     'admin'
-- );