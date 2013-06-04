CREATE TABLE karma(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Element' NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
user_id INT NOT NULL,
post_id INT NOT NULL,
autor_id INT NOT NULL,
checked ENUM('0', '1') DEFAULT '0' NOT NULL,
type ENUM('up', 'down') NOT NULL,
write_type ENUM('post', 'comment') NOT NULL,
PRIMARY KEY(id)
)