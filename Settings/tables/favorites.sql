CREATE TABLE favorites(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_Element' NOT NULL,
created_time VARCHAR(255) NOT NULL,
post_id INT NOT NULL,
user_id INT NOT NULL,
PRIMARY KEY(id)
)