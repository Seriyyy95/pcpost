CREATE TABLE journal_comments(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_Element' NOT NULL,
created_time VARCHAR(255) NOT NULL,
post_id INT NOT NULL,
comment_id VARCHAR(255) NOT NULL,
user_id INT NOT NULL,
new ENUM('0', '1') DEFAULT '1' NOT NULL,
showed ENUM('0', '1') DEFAULT '0' NOT NULL,
count INT DEFAULT '1' NOT NULL,
PRIMARY KEY(id)
)