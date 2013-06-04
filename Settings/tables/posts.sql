CREATE TABLE posts(
id INT NOT NULL AUTO_INCREMENT,
parent_id INT DEFAULT '1'  NOT NULL,
class VARCHAR(40) DEFAULT 'Z_TreeElement' NOT NULL,
name VARCHAR(255) NOT NULL,
num_open INT DEFAULT '0' NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
child_table VARCHAR(50) DEFAULT 'comments' NOT NULL,
parent_table VARCHAR(50) DEFAULT 'sections' NOT NULL,
description VARCHAR(100000),
text VARCHAR(1000000),
autor INT NOT NULL,
tags VARCHAR(255),
hide INT DEFAULT '0' NOT NULL,
karma INT DEFAULT '0' NOT NULL,
PRIMARY KEY(id)
)