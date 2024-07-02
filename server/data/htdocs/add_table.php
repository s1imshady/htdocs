<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Добавление новой таблицы</title>
</head>
<body>
<form action="" method="post">
  <div>
    <label>Создайте таблицу с указанным именем или измените тип данных в существующей таблице</label>
  </div>
  <div>
    <label for="table_name">Название вида спорта:</label>  
    <input type="text" id="table_name" name="table_name" required>
  </div>
  <div>
    <label for="result_type">Выберите тип фиксации результата:</label>    
    <select id="result_type" name="result_type">
      <!--Создаются поля 'число_попыток' (int) и 'результат' (float)-->
      <option value="attemps">Попытки</option>
      <!--Создаются поля 'дистанция' (int) и 'время' (time)-->
      <option value="distance">Дистанция</option>
      <!--Создаются поля стадия (строка) и счёт (строка)-->
      <option value="score">Счёт</option>     
    </select>
  </div>
    <input type="submit" name = "create" value="Создать таблицу">
	<input type="submit" name = "edit" value="Изменить тип полей">
  <div>
    <label for="table_name">Просмотр списка столбцов таблицы</label>
    <input type="submit" name = "show" value="Показать">	
</form>


<?php
$servername = "localhost";
$username = "new_user";
$password = "123";
$dbname = "competitions";
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Подключение к базе данных
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset", $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Проверка наличия данных от пользователя
if (isset($_POST['table_name']) && isset($_POST['create'])) {
    $tableName = $_POST['table_name'];
    $resultType = $_POST['result_type'];

    $stmt = $pdo->prepare("SELECT name FROM sports WHERE name = '$tableName'");    
    $stmt->execute();

    //Таблица с введённым названием существует
    if($stmt->rowCount() > 0) {
        echo "Таблица $tableName уже существует. Укажите другое название.";    
    }
    else {
        $stmt = $pdo->prepare("INSERT INTO sports VALUES ('$tableName')");    
        $stmt->execute();        

        // Подготовка SQL-запроса
        $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS $tableName (
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                id_part INT(6) UNSIGNED,
                                added_by VARCHAR(20)                                
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");      
        
        $stmt->execute();                       

        //В зависимости от выбранного типа создаются разные столбцы
        switch ($resultType)
        {
            case 'attemps':                
                $stmt = $pdo->prepare("ALTER TABLE $tableName ADD COLUMN attemps INT(6) UNSIGNED,
                                       ADD COLUMN result FLOAT");    
                $stmt->execute(); 
                echo "Таблица $tableName с полями id, part_id, added_by, attemps, result успешно создана.";  
                break;
            case 'distance':                
                $stmt = $pdo->prepare("ALTER TABLE $tableName ADD COLUMN dist INT(6) UNSIGNED,
                                       ADD COLUMN time TIME");
                $stmt->execute(); 
                echo "Таблица $tableName с полями id, part_id, added_by, dist, time успешно создана.";
                break;
            case 'score':                
                $stmt = $pdo->prepare("ALTER TABLE $tableName ADD COLUMN round VARCHAR(20),
                                       ADD COLUMN score VARCHAR(20)"); 
                $stmt->execute(); 
                echo "Таблица $tableName с полями id, part_id, added_by, round, score успешно создана.";               
                break;        
        }           
    }
}
elseif (isset($_POST['table_name']) && isset($_POST['edit']))
{
    $tableName = $_POST['table_name'];
    $resultType = $_POST['result_type'];

    $stmt = $pdo->prepare("SELECT name FROM sports WHERE name = '$tableName'");    
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "Таблица $tableName отсутствует в базе. Попробуйте указать другое название.";    
    }
    else
	{	
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $tableName");
	    $stmt->execute();
		  
		$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
		$col4 = $columns[3];
		$col5 = $columns[4];	        	
	    
	    switch($resultType)
        {  		    	 
            case 'attemps': 
                $stmt = $pdo->prepare("SELECT COLUMN_NAME 
                                FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_SCHEMA = '$dbname' 
                                AND TABLE_NAME = '$tableName'
                                AND COLUMN_NAME IN ('attemps', 'result')");
				$stmt->execute();				
								
                if ($stmt->rowCount() > 0) {
                    echo "Названия столбцов в таблице соответствуют указанным";
                }					
				else
				{				
                    $stmt = $pdo->prepare("ALTER TABLE $tableName CHANGE $col4 attemps INT(6) UNSIGNED,
					                                              CHANGE $col5 result FLOAT");                  									   
                    $stmt->execute(); 				
                    echo "Столбцы в таблице $tableName успешно заменены.";  
				}
				
                break;
            case 'distance':
                $stmt = $pdo->prepare("SELECT COLUMN_NAME 
                                FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_SCHEMA = '$dbname' 
                                AND TABLE_NAME = '$tableName'
                                AND COLUMN_NAME IN ('dist', 'time')");    
				$stmt->execute();				
    				 
                if ($stmt->rowCount() > 0) {
				    echo "Названия столбцов в таблице соответствуют указанным";   
                }					
				else
				{			
                    $stmt = $pdo->prepare("ALTER TABLE $tableName CHANGE $col4 dist INT(6) UNSIGNED,
					                                              CHANGE $col5 time TIME"); 
                    $stmt->execute(); 
                    echo "Столбцы в таблице $tableName успешно заменены.";
				}
                break;
            case 'score':      
                $stmt = $pdo->prepare("SELECT COLUMN_NAME 
                                FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_SCHEMA = '$dbname' 
                                AND TABLE_NAME = '$tableName'
                                AND COLUMN_NAME IN ('round', 'score')");
				$stmt->execute();
				
                if($stmt->rowCount() > 0) {				
                    echo "Названия столбцов в таблице соответствуют указанным";  
                }					
				else
				{
                    $stmt = $pdo->prepare("ALTER TABLE $tableName CHANGE $col4 round VARCHAR(20),
					                                              CHANGE $col5 score VARCHAR(20)"); 
                    $stmt->execute(); 
                    echo "Столбцы в таблице $tableName успешно заменены."; 
                }					
                break;             				
        }  	    
    }    
}
if (isset($_POST['table_name']) && isset($_POST['show'])) {
    $tableName = $_POST['table_name'];
    $stmt = $pdo->prepare("SELECT name FROM sports WHERE name = '$tableName'"); 
	$stmt->execute();
	
	if ($stmt->rowCount() == 0) {				
        echo "Таблица $tableName отсутствует в базе. Попробуйте указать другое название.";  
    }
  	else
	{
	    $stmt = $pdo->prepare("SHOW COLUMNS FROM $tableName");   
        $stmt->execute();    
		$stmt->setFetchMode(PDO::FETCH_ASSOC);   
		$result = $stmt->fetchAll();	

		if (count($result) > 0) {    
			echo "<table border='1'>";
			echo "<tr><th>Столбец</th><th>Тип</th></tr>";    
      
			foreach($result as $row) {
				echo "<tr><td>" . $row["Field"] . "</td><td>" . $row["Type"] . "</td></tr>";
            }      
      
            echo "</table>";
        }
    }	
}

$pdo = null;
?>

</body>
</html>