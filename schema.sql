CREATE DATABASE `doingsdone`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE `doingsdone`;

CREATE TABLE `users` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` char(128) NOT NULL,
	`email` char(128) NOT NULL UNIQUE,
	`password` char(64) NOT NULL,
	`registration_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`contacts` char(128) NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `projects` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` char(128) NOT NULL UNIQUE,
	`user_id` int NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tasks` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` char(128) NOT NULL UNIQUE,
	`file` char(128) NULL,
	`create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`complete_date` DATETIME NULL,
	`deadline` DATETIME NULL,
	`user_id` int NOT NULL,
	`project_id` int NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `projects` ADD CONSTRAINT `projects_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `tasks` ADD CONSTRAINT `tasks_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `tasks` ADD CONSTRAINT `tasks_fk1` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`);
