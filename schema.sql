CREATE DATABASE `doingsdone`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE `doingsdone`;

CREATE TABLE `users` (
	`id`  NOT NULL AUTO_INCREMENT,
	`name` char(128),
	`email` (128) UNIQUE,
	`password` char(64),
	`contacts` char(128),
	`registration_date` DATE,
	PRIMARY KEY (`id`)
);

CREATE TABLE `projects` (
	`id`  NOT NULL AUTO_INCREMENT,
	`name` (128) UNIQUE,
	`u_id` (128),
	PRIMARY KEY (`id`)
);

CREATE TABLE `tasks` (
	`id`  NOT NULL AUTO_INCREMENT,
	`name` (128) UNIQUE,
	`file` char(128),
	`create_date` DATE,
	`complete_date` DATE,
	`deadline` DATE,
	`u_id` (128),
	`p_id` (128),
	PRIMARY KEY (`id`)
);
