-- изменить кодировку БД
ALTER DATABASE database_name DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
-- изменить кодировку таблицы
ALTER TABLE table_name DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
ALTER TABLE `table_name` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- инфа по одной таблице (в том числе кодировка)
show table status like 'b_iblock_section';
-- инфа по всем таблицам (в том числе кодировка)
show table status;
-- инфа по БД
SHOW VARIABLES;
-- изменение всех таблиц
SELECT CONCAT('ALTER TABLE ',TABLE_NAME,' CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;') 
FROM INFORMATION_SCHEMA.TABLES
WHERE table_schema = 'ofnext';
--добавление столбца
ALTER TABLE s_control ADD IS_ONLINE VARCHAR(1) NULL;
--удаление столбца
ALTER TABLE s_control DROP COLUMN IS_ONLINE;
--редактирование столбца
ALTER TABLE s_control ALTER COLUMN IS_ONLINE SET DEFAULT "Y";
--создание таблицы
CREATE TABLE s_certificate
(
    ID INT AUTO_INCREMENT,
    USER_ID INT,
    EVENT_ID INT,
    CERTIFICATE VARCHAR(50),
	PRIMARY KEY (ID)
)
--удаление таблицы
DROP TABLE s_certificate
--добавление записи
INSERT INTO s_price_drop (USER_ID, PRODUCT_ID, EMAIL) 
VALUES (val, val2, val3,... val N);
--удаление записи
DELETE FROM s_price_drop WHERE;
--обновление записи
UPDATE Laptop SET hd = ram/2 WHERE hd < 10;