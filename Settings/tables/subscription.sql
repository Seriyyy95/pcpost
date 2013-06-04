CREATE TABLE subscription(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_Element' NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
target_id INT,
user_id INT,
PRIMARY KEY(id)
)