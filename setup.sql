CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  password VARCHAR(100),
  email VARCHAR(200),
  role VARCHAR(50)
);

INSERT INTO users (username,password,email,role) VALUES
('alice','alicepass','alice@example.com','user'),
('bob','bobpass','bob@example.com','admin');
