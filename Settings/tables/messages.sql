CREATE TABLE messages(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_BbCodeElement' NOT NULL,
created_time VARCHAR(255) NOT NULL,
user_id VARCHAR(40) BINARY NOT NULL,
adresat_id VARCHAR(40) BINARY NOT NULL,
autor_id VARCHAR(40) BINARY NOT NULL,
text VARCHAR(1024),
is_read ENUM('1','0') DEFAULT '0' NOT NULL,
PRIMARY KEY(id)
)