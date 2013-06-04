CREATE TABLE groups(
id INT NOT NULL AUTO_INCREMENT,
class VARCHAR(40) DEFAULT 'Z_Group' NOT NULL,
created_time VARCHAR(255) NOT NULL,
group_name VARCHAR(255) NOT NULL,
PRIMARY KEY(id)
);
INSERT INTO groups (`group_name`) VALUES ('Администраторы');
INSERT INTO groups (`group_name`) VALUES ('Гости');
INSERT INTO groups (`group_name`) VALUES ('Системные');
INSERT INTO groups (`group_name`) VALUES ('Пользователи')