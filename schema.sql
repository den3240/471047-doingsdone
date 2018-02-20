CREATE DATABASE `doingsdone`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE `doingsdone`;

CREATE TABLE `users` (
	`id`  INT NOT NULL AUTO_INCREMENT,
	`name` CHAR(128),
	`email` CHAR(128) UNIQUE,
	`password` CHAR(64),
	`contacts` CHAR(128),
	`registration_date` DATE,
  UNIQUE KEY (`email`),
	PRIMARY KEY (`id`)
);

CREATE TABLE `projects` (
	`id` INT  NOT NULL AUTO_INCREMENT,
	`name` CHAR(128),
	`users_id` CHAR(128),
  UNIQUE KEY (`name`),
	PRIMARY KEY (`id`)
);

CREATE TABLE `tasks` (
	`id` INT  NOT NULL AUTO_INCREMENT,
	`name` CHAR(128) UNIQUE,
	`file` CHAR(128),
	`create_date` DATE,
	`complete_date` DATE,
	`deadline` DATE,
	`users_id` CHAR(128),
	`projects_id` CHAR(128),
  UNIQUE KEY (`name`),
	PRIMARY KEY (`id`)
);
