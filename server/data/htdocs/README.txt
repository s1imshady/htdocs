Это файл README для формы №3 (add_table)

Форма является набором следующих элементов: 
1) Поле ввода - здесь пользователь указывает название вида спорта (название таблицы);
2) Выпадающий список для выбора типа фиксации результата (попытки, дистанция, счёт);
3) Кнопка создания таблицы - при её нажатии создаётся таблица или выводится сообщение о том,
что таблица уже существует;
4) Кнопка для изменения типа полей - при  её нажатии либо меняется название 
и тип зависимых полей, либо выводится сообщение о том, 
что таблица не найдена/названия полей соответствуют введённым;
5) Кнопка для просмотра структуры таблицы - выводит таблицу с названиями полей и типами данных

Таблица с названием вида спорта содержит 5 полей: (3 фиксированных - 'id', 'id_part' и 'added_by')
и 2 зависимых от типа результата 
Таблица 'sports' содержит единственный столбец - названия добавленных видов спорта

Типы данных и названия зависимых полей:
Возможны 3 варианта выбора типа фиксации результата:
1) Попытки (для видов спорта, где у спортсменов есть несколько попыток, например, прыжки в длину)
В этом случае в таблице будут поля: attemps (int(6) unsigned) - число попыток, result (FLOAT) - результат;
2) Дистанция (для циклических видов спорта: бег, плавание, велоспорт и т. д.)
Поля: dist (int(6) unsigned) - пройденная дистанция и time (TIME) - показанное время
3) Счёт (для игровых видов спорта: теннис, бадминтон и т. д.)
Поля: round (varchar(20)) - стадия соревнований (например, 1/8 или 1/2), score (varchar(20)) - счёт.