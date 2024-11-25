CREATE DATABASE IF NOT EXISTS babybuddy;

USE babybuddy;

-- Tabela de Babysitters
CREATE TABLE babysitters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    hourly_rate DECIMAL(10, 2),
    qualifications TEXT,
    photo VARCHAR(255),
    experience TEXT,
    latitude FLOAT,
    longitude FLOAT
);

-- Tabela de Guardians
CREATE TABLE guardians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    address TEXT,
    photo VARCHAR(255)
);

-- Tabela de Proposals
CREATE TABLE proposals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT,
    guardian_id INT,
    status ENUM('pendente', 'aceita', 'rejeitada') DEFAULT 'pendente',
    hourly_rate DECIMAL(10, 2),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    response TEXT,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id),
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

-- Tabela de Messages
CREATE TABLE `messages` (
    `id` int NOT NULL AUTO_INCREMENT,
    `proposal_id` int DEFAULT NULL,
    `sender_id` int DEFAULT NULL,
    `sender_type` enum('babysitter', 'guardian') DEFAULT NULL,
    `receiver_id` int DEFAULT NULL,
    `receiver_type` enum('babysitter', 'guardian') DEFAULT NULL,
    `message` text,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `proposal_id` (`proposal_id`),
    CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Tabela de Responses
CREATE TABLE responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proposal_id INT,
    babysitter_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id),
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id)
);

-- Tabela de Notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id)
);

-- Tabela de Reviews
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT NOT NULL,
    guardian_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id),
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

ALTER TABLE proposals
MODIFY COLUMN status ENUM('pendente', 'aceita', 'rejeitada', 'em_andamento', 'concluida') DEFAULT 'pendente';

ALTER TABLE messages CHANGE sender_type sender_type ENUM('babysitter', 'guardian');

