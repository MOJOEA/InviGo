/* =========================
   DROP TABLES (ถ้ามีอยู่)
   ========================= */
DROP TABLE IF EXISTS Otp_Codes;
DROP TABLE IF EXISTS Registrations;
DROP TABLE IF EXISTS Event_Images;
DROP TABLE IF EXISTS Events;
DROP TABLE IF EXISTS Users;

/* =========================
   USERS
   ========================= */
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    birth_date DATE,
    gender VARCHAR(20),
    profile_image VARCHAR(255) DEFAULT 'https://api.dicebear.com/9.x/dylan/svg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT uq_users_email UNIQUE (email)
);

/* =========================
   EVENTS
   ========================= */
CREATE TABLE Events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    max_participants INT NOT NULL,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_events_user
        FOREIGN KEY (user_id)
        REFERENCES Users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

/* =========================
   EVENT IMAGES
   ========================= */
CREATE TABLE Event_Images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_event_images_event
        FOREIGN KEY (event_id)
        REFERENCES Events(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

/* =========================
   REGISTRATIONS
   ========================= */
CREATE TABLE Registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    checked_in BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id)
        REFERENCES Events(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_registrations_user
        FOREIGN KEY (user_id)
        REFERENCES Users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_event_user UNIQUE (event_id, user_id)
);

/* =========================
   OTP CODES
   ========================= */
CREATE TABLE Otp_Codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_otp_registrations
        FOREIGN KEY (registration_id)
        REFERENCES Registrations(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

/* =========================
   INDEXES (เพิ่มประสิทธิภาพ)
   ========================= */
CREATE INDEX idx_events_user_id ON Events(user_id);
CREATE INDEX idx_event_images_event_id ON Event_Images(event_id);
CREATE INDEX idx_registrations_event_id ON Registrations(event_id);
CREATE INDEX idx_registrations_user_id ON Registrations(user_id);
CREATE INDEX idx_otp_registrations ON Otp_Codes(registration_id);
CREATE INDEX idx_otp_code ON Otp_Codes(otp_code);
