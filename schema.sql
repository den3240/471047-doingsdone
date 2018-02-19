CREATE DATABASE doingsdone;
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE doingsdone;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    email CHAR(128),
    password CHAR(64),
    contacts CHAR(128),
    registration_date DATE
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    u_id CHAR(128)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    file CHAR(128),
    create_date DATE,
    complete_date DATE,
    deadline DATE,
    u_id CHAR(128)
);

SELECT u.id, p.name AS 
p_name 
FROM users u INNER JOIN 
projects p ON 
p.u_id = u.id;

SELECT u.id, t.name AS 
t_name 
FROM users u INNER JOIN 
tasks t ON 
t.u_id = u.id;