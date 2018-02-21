USE `doingsdone`;

/* Добавляю данные в таблицу USERS */
INSERT INTO `users` (`name`, `email`, `password`, `contacts`) VALUES ('Игнат', 'ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', 'ignat.v@gmail.com'), ('Леночка', 'kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', 'kitty_93@li.ru'), ('Руслан', 'warrior07@mail.ru', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', 'warrior07@mail.ru');

/* Добавляю данные в таблицу projects */
INSERT INTO `projects` (`name`, `user_id`) VALUES ('Входящие', 3), ('Учеба', 3), ('Работа', 3), ('Домашние дела', 3), ('Авто', 3);

/* Добавляю данные в таблицу tasks */
INSERT INTO `tasks` (`name`, `deadline`, `user_id`, `project_id`) VALUES ('Собеседование в IT компании', '2018-02-12', 3, 4), ('Выполнить тестовое задание', '2018-06-25', 3, 4), ('Сделать задание первого раздела', '2018-02-22', 3, 3), ('Встреча с другом', '2018-02-10', 3, 2), ('Купить корм для кота', NULL, 3, 5), ('Заказать пиццу', NULL, 3, 5);


/*  */
SELECT `name` FROM `projects` WHERE `user_id` = 3;
SELECT * FROM `tasks` WHERE `project_id` = 5;
UPDATE `tasks` SET `complete_date` = '2018-02-15' WHERE `id` = 4;
SELECT * FROM `tasks` WHERE `deadline` = ADDDATE(CURDATE(), INTERVAL 1 DAY);
UPDATE `tasks` SET `name` = 'Заказать суши' WHERE `id` = 6;
