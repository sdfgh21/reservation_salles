reserv_sallesreserv_sallesperformance_schemaCREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (fullname, email, password)
VALUES ('Admin Test', 'admin@example.com', '$2y$10$g7e8c6j1FqkwlUjbE9gDSeiVuK9zXlQcA.1bG9m3IJMN9MrcROp6S');
