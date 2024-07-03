<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Переход на Авторизацию</title>

</head>
<body>
    <!-- Кнопка для перехода на страницу авторизации -->
    <form action="auth_form.php">
        <input type="submit" value="Перейти к авторизации">
    </form>
    <form action="" method="post">
        <input type="text" name="sportType" placeholder="Введите вид спорта" required>
        <input type="submit" value="Поиск">
    </form>
</body>
</html>


<?php
$servername = "localhost"; // адрес сервера
$username = "root"; // имя пользователя
$password = ""; // пароль
$dbname = "competitions"; // имя базы данных

$conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["sportType"])) {
    $sportType = $_POST["sportType"];

    // Подготовка запроса на поиск
    $stmt = $conn->prepare("SELECT dist, Id_sw, Id_part, Time, Added_by,time FROM $sportType");        
    $stmt->execute();

    // Установка результата в ассоциативный массив
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {    
		echo "<table border='1'>";
		echo "<tr><th>Номер</th><th>Номер участника</th><th>Дремя</th><th>Дистанция</th><th>Редактор</th></tr>";    
      
		foreach($result as $row) {
			echo "<tr><td>" . $row["Id_sw"] . "</td><td>" . $row["Id_part"] . "</td><td>" . $row["Time"] . "</td><td>" . $row["dist"] . "</td><td>" . $row["Added_by"] . "</td></tr>";
        }    
		echo "</table>";
    }
	else {
		echo "0 results";
	}
}

$conn = null; 
?>