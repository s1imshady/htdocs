<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Форма Аутентификации</title>
</head>
<body>
  <h1>Аутентификация</h1>
  <form formaction="auth_form.php" method="POST">
    <label for="login">Логин:</label>
    <input type="text" id="login" name="login"><br>
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password"><br><br>
    <input name="btn" type="submit" value="Войти">
    <input name="btn1" type="submit" value="Зарегистрироваться">
  </form>
</body>
</html>

<?php

$db_host = "localhost";
$db_user = "host"; 
$db_password = "host";  // ТУТ МОЖЕТ БЫТЬ ПОЛЬЗОВАТЕЛЬ, У КОТОРОГО ПРАВА SELECT, insert К БД
$db_base = "competitions"; 
$db_table_user = "user";

try{
  $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
  $db->exec("set names utf8");

  if(!empty($_POST['btn'])){
    if(($_POST['login'] !== '') && ($_POST['password'] !== '')){
      $login = $_POST['login'];
      $password = $_POST['password'];
      $data_check = $db->query("SELECT * FROM $db_table_user WHERE `login` = '$login'");
      $row = $data_check->fetch(PDO::FETCH_ASSOC);
      if (($data_check-> rowCount() > 0) && (MD5($password) === $row['password']) && (($login === $row['login']))){
        if ($row['role'] === 0){ // MA
          echo "Выполняется вход в учетную запись, подождите...";
          //передача данных для дальнейшего использования в др формах 
          session_start();
          $_SESSION['id_user'] = $row['id_user']; 
          $_SESSION['login'] = $login;
          sleep(2);
          header("Location: http://localhost/add_table.php"); // тут должна быть форма 3
          exit;
        }
        if ($row['role'] === 1){ // ref
          echo "Выполняется вход в учетную запись, подождите...";
          //передача данных для дальнейшего использования в др формах 
          session_start();
          $_SESSION['id_user'] = $row['id_user'];  
          $_SESSION['login'] = $login;
          sleep(2);
          header("Location: http://localhost/add_result.php"); // тут должна быть форма 4
          exit;
        }
        if ($row['role'] === 2){ // part
          echo "Выполняется вход в учетную запись, подождите...";
          //передача данных для дальнейшего использования в др формах 
          session_start();
          $_SESSION['id_user'] = $row['id_user']; 
          $_SESSION['login'] = $login;
          sleep(2);
          header("Location: http://localhost/part_page.php"); // тут должна быть форма 5
          exit;
        }
      }
    }
    else {
      echo "Заполните поля: 'Логин', 'Пароль'";
    }
  }
  if(!empty($_POST['btn1'])){
    header("Location: http://localhost/register_form.php"); 
    exit;
  }

}
catch (PDOException $e) {

}

?>