<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Страница участника</title>
  </head>
  <body>
    <form action="" method="post">
	<p>Введите вид спорта, по которому хотите просмотреть свои результаты: <input type="text" name="name_sport"></p>
	<p><input type="submit" value="Проверить результаты" name="done"></p>
	</form>
   </body>
</html>

<?php
session_start();

if (isset($_POST['done']) && !empty($_POST['login'])) {
    $_SESSION['login'] = $_POST['login'];
}

    $host = 'localhost';  
    $user = 'root';  
    $pass = ''; 
    $db_name = 'competitions'; 
	$conn = new mysqli($host, $user, $pass, $db_name);

    if ($conn->connect_error) {
		die("Ошибка подключения: " . $conn->connect_error);
	}
	
$query = "SELECT id_user FROM user WHERE login = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['login']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_user = $row['id_user'];
} else {
    die("Пользователь с логином {$_SESSION['login']} не найден");
}

if (isset($_POST['done'])) 
{	
	if (!empty($_POST['name_sport']))
	{
		$name_sport = $_POST['name_sport'];
        
        $query = "SELECT * FROM sports WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $name_sport);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $query = "SELECT * FROM $name_sport WHERE id_part = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr>";
                while ($field_info = $result->fetch_field()) {
                    echo "<th>{$field_info->name}</th>";
                }
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>{$cell}</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Вы не участвовали в данном соревновании!<br>";
            }
        } else {
            echo "Соревнования по такому виду спорта не проводятся.<br>";
        }
        
        $stmt->close();
	}
	else
	{
		echo 'Введите название вида спорта!<br>';
	}
	$conn->close();
}

?> 