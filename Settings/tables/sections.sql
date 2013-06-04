CREATE TABLE sections(
id INT NOT NULL AUTO_INCREMENT,
parent_id INT DEFAULT '1'  NOT NULL,
class VARCHAR(40) DEFAULT 'Z_Element' NOT NULL,
name VARCHAR(255) NOT NULL,
created_time VARCHAR(255) DEFAULT '0' NOT NULL,
child_table VARCHAR(50) DEFAULT 'sections' NOT NULL,
hide INT DEFAULT '0' NOT NULL,
PRIMARY KEY(id)
);
INSERT INTO sections (parent_id, name) VALUES ('0','Разделы');
INSERT INTO sections (parent_id, name) VALUES ('1','Вопросы');
INSERT INTO sections (parent_id, name) VALUES ('1','Администрирование')