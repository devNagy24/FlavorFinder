-- CREATE TABLE users (
--                        id INT AUTO_INCREMENT PRIMARY KEY,
--                        username VARCHAR(50) UNIQUE NOT NULL,
--                        password VARCHAR(255) NOT NULL,
--                        email VARCHAR(255) UNIQUE NOT NULL,
--                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );
--
-- CREATE TABLE bookmarks (
--                            id INT AUTO_INCREMENT PRIMARY KEY,
--                            username VARCHAR(255) NOT NULL,
--                            recipe_id VARCHAR(255) NOT NULL,
--                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--                            FOREIGN KEY (username) REFERENCES users(username)
-- );