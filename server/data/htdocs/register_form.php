<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Форма регистрации</title>
    <script>
        function showFields(type) {
            if (type === "participant") {
                document.getElementById("participantFields").style.display = "block";
                document.getElementById("refFields").style.display = "none";
            } else if (type === "ref") {
                document.getElementById("participantFields").style.display = "none";
                document.getElementById("refFields").style.display = "block";
            }
        }
    </script>
</head>
<body>
    <h1>Регистрация</h1>
    <form formaction="register_form.php" method="POST">
        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" ><br>
        

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password"><br><br>

        <h2>Тип регистрации:</h2>
        <label for="participantRadio">
             <input type="radio" id="participantRadio" name="type" value="participant" onchange="showFields('participant')" checked> 
            Как участник
        </label><br>
        <label for="refRadio">
            <input type="radio" id="refRadio" name="type" value="ref" onchange="showFields('ref')"> 
            Как ref или MA
        </label><br><br>

        <div id="participantFields" style="display: block;">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name"><br>

            <label for="surname">Фамилия:</label>
            <input type="text" id="surname" name="surname"><br>

            <label for="birthdate">Дата рождения:</label>
            <input type="date" id="birthdate" name="birthdate" ><br>
        </div>

        <div id="refFields" style="display: none;">
            <label for="refCode">Введите код регистрации:</label>
            <input type="text" id="refCode" name="refCode"><br>
        </div><br>
        <input name = "btn" type="submit" value = "Зарегистрироваться">
        <input name = "btn1" type="submit" value = "Назад">
    </form>
</body>
</html>
<?php
// подключение к БД
$db_host = "localhost";
$db_user = "root"; 
$db_password = "1234"; // ТУТ МОЖЕТ БЫТЬ ПОЛЬЗОВАТЕЛЬ У КОТОРОГО БУДУТ ПРАВА ТОЛЬКО НА INSERT, SELECT К БД
$db_base = "competitions"; 
$db_table_user = "user";
$db_table_add_info = "add_info";

$code_MA = 'MA';
$code_ref = 'referee';
try{
    $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
    $db->exec("set names utf8");

    if (isset($_POST['type'])){
        if (!empty($_POST['btn1'])){
            header("Location: http://localhost/auth_form.php");
            exit;
        }
        
        $selectedType = $_POST['type'];

        if ($selectedType === 'participant') {
            if (!empty($_POST['btn'])){
                if (($_POST['login'] !== '') && ($_POST['password'] !== '')){
                
                    if (($_POST['name'] !== '') && ($_POST['surname'] !== '') && ($_POST['birthdate'] !== '')){

                        $login = $_POST['login'];
                        $data_check = $db->query("SELECT `login` FROM $db_table_user WHERE `login` = '$login'");
                        if ($data_check-> rowCount() > 0){
                            echo "Пользователь с таким же логином уже существует, измените поле 'Логин'!";
                            exit;
                        } else {

                            $login = $_POST['login'];
                            $password = $_POST['password'];
                            $role = 2;
                            $data_to_user = array('login' => $login, 'password' => $password, 'role' => $role);

                            $query_insert1 = $db->prepare("INSERT INTO $db_table_user (login, password, role) values (:login, MD5(:password), :role)");
                            $query_insert1->execute($data_to_user);

                            $query_sel_id = $db->query("SELECT `id_user` FROM $db_table_user WHERE `login` = '$login'");
                            $row = $query_sel_id->fetch(PDO::FETCH_ASSOC);
                            $id_user = $row['id_user'];
                            $name = $_POST['name'];
                            $surname = $_POST['surname'];
                            $birthdate = $_POST['birthdate'];
                            $data_to_add_info = array('id_user' => $id_user, 'surname' => $surname, 'name' => $name, 'DOB' => $birthdate);

                            $query_insert2 = $db->prepare("INSERT INTO $db_table_add_info (id_user, surname, name, DOB) values (:id_user, :surname, :name, :DOB)");
                            $query_insert2->execute($data_to_add_info);

                            echo "Вы успешно зарегистрировались, как участник!"; 

                            sleep(2);
                            header("Location: http://localhost/auth_form.php");
                            exit;
                        }
                    } else {
                        echo "Заполните поля:'Имя', 'Фамилия', 'Дата рождения'!";
                    }
                } else {
                    echo "Заполните поля: 'Логин', 'Пароль'!";
                }
            }

    } else if ($selectedType === 'ref') {
        if (!empty($_POST['btn'])){
            if (($_POST['login'] !== '') && ($_POST['password'] !== '')){
                if ($_POST['refCode'] !== ''){
                    $code = $_POST['refCode'];

                    if ($code === $code_MA){
                        $login = $_POST['login'];
                        $password = $_POST['password'];
                        $role = 0;
                        $data_to_user = array('login' => $login, 'password' => $password, 'role' => $role);
                        $query_insert1 = $db->prepare("INSERT INTO $db_table_user (login, password, role) values (:login, MD5(:password), :role)");
                        $query_insert1->execute($data_to_user);
                        echo "Вы успешно зарегистрировались, как мастер-админ!";
                        sleep(2);
                        header("Location: http://localhost/auth_form.php");
                        exit;
                            
                    }
                    else if ($code === $code_ref){
                        $login = $_POST['login'];
                        $password = $_POST['password'];
                        $role = 1;
                        $data_to_user = array('login' => $login, 'password' => $password, 'role' => $role);
                        $query_insert1 = $db->prepare("INSERT INTO $db_table_user (login, password, role) values (:login, MD5(:password), :role)");
                        $query_insert1->execute($data_to_user);
                        echo "Вы успешно зарегистрировались, как судья!"; 
                        sleep(2);
                        header("Location: http://localhost/auth_form.php");
                        exit;
                    }
                    else{
                        echo "Введенный код доступа недействителен! Попробуйте еще раз!";
                    }
                } else {echo "Введите код доступа!";}
            }  else {
                echo "Заполните поля: 'Логин', 'Пароль'!";
            } 
        }    
    } else {
        echo "Не выбран тип регистрации, выберете вариант регистрации и/или не заполнены все поля!";
    }
    }

} catch (PDOException $e) {
    
    print "Ошибка!: " . $e->getMessage() . "<br/>";
}



?>