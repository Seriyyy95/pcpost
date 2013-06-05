CREATE TABLE users(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_User' NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
name VARCHAR(255) NOT NULL,
login VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
birth_date VARCHAR(255) NOT NULL,
pol ENUM('M', 'F') DEFAULT 'M' NOT NULL,
country VARCHAR(255) NOT NULL,
city VARCHAR(255) NOT NULL,
about_me VARCHAR(10000),
image VARCHAR(255) DEFAULT 'Images/avatar.jpg' NOT NULL,
reg_ip VARCHAR(100) NOT NULL,
online_time VARCHAR(100) DEFAULT '0' NOT NULL,
auth_id INT,
user_group INT DEFAULT '4' NOT NULL,
karma INT DEFAULT '0' NOT NULL,
reseted ENUM('0', '1') DEFAULT '0' NOT NULL,
PRIMARY KEY(id)
);
INSERT INTO users (name, login, password, email, birth_date, reg_ip, user_group) VALUES ('admin','admin', 'euMa3Ale', 'Seriyyy95@mail.ru', now(), 'null', '1');
INSERT INTO users (name, login, password, email, birth_date, reg_ip, user_group) VALUES ('anonimyus', 'anonimyus', 'default', 'default', now(), 'null', '2');
INSERT INTO users (class,name, login, password, email, birth_date, reg_ip, user_group) VALUES ('Z_SystemUser','Система', 'Система', 'default', 'default', now(), 'null', '3')