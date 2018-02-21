/* Добавляю данные в таблицу USERS */
INSERT INTO `users` SET name = 'Игнат', email = 'ignat.v@gmail.com', password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', contacts = 'ignat.v@gmail.com';
INSERT INTO `users` SET name = 'Леночка', email = 'kitty_93@li.ru', password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', contacts = 'kitty_93@li.ru';
INSERT INTO `users` SET name = 'Руслан', email = 'warrior07@mail.ru', password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', contacts = 'warrior07@mail.ru';

/* Добавляю данные в таблицу projects */
INSERT INTO `projects` SET name = 'Все', user_id = 3;
INSERT INTO `projects` SET name = 'Входящие', user_id = 3;
INSERT INTO `projects` SET name = 'Учеба', user_id = 3;
INSERT INTO `projects` SET name = 'Работа', user_id = 3;
INSERT INTO `projects` SET name = 'Домашние дела', user_id = 3;
INSERT INTO `projects` SET name = 'Авто', user_id = 3;

/* Добавляю данные в таблицу tasks */
INSERT INTO `tasks` SET name = 'Собеседование в IT компании', deadline = ADDDATE('2018-02-12', INTERVAL 0 DAY), user_id = 3, project_id = 4;
INSERT INTO `tasks` SET name = 'Выполнить тестовое задание', deadline = ADDDATE('2018-06-25', INTERVAL 0 DAY), user_id = 3, project_id = 4;
INSERT INTO `tasks` SET name = 'Сделать задание первого раздела', deadline = ADDDATE('2018-02-22', INTERVAL 0 DAY), user_id = 3, project_id = 3;
INSERT INTO `tasks` SET name = 'Встреча с другом', deadline = ADDDATE('2018-02-10', INTERVAL 0 DAY), user_id = 3, project_id = 2;
INSERT INTO `tasks` SET name = 'Купить корм для кота', user_id = 3, project_id = 5;
INSERT INTO `tasks` SET name = 'Заказать пиццу', user_id = 3, project_id = 5;

/*  */
SELECT name FROM `projects` WHERE user_id = 3;
SELECT * FROM `tasks` WHERE project_id = 5;
UPDATE `tasks` SET complete_date = ADDDATE('2018-02-15', INTERVAL 0 DAY) WHERE id = 4;
SELECT * FROM `tasks` WHERE deadline = ADDDATE(CURDATE(), INTERVAL 1 DAY);
UPDATE `tasks` SET name = 'Заказать суши' WHERE id = 6;
