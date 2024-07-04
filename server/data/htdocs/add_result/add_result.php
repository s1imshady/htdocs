<link rel="stylesheet" href="style_add_result.css">

<?php

session_start();

if (!isset($_SESSION['login'])) $_SESSION['login'] = "test_ref";

if (isset($list_column)) print_r($list_column);

if (isset($_SESSION['add_result']['table_pole'])){
for ($i=0;$i<count($_SESSION['add_result']['table_pole']);$i++){
	if (isset($_GET['but_'.$i])){	
		unset($_SESSION['add_result']['table_pole'][$i]);
		$_SESSION['add_result']['table_pole'] = array_filter($_SESSION['add_result']['table_pole'], function($key) use ($i) {
			return $key != $i;
		}, ARRAY_FILTER_USE_KEY);
		
		$_SESSION['add_result']['table_pole'] = array_values($_SESSION['add_result']['table_pole']);
	}
}
}
error_reporting(0);
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'xih677r';
$db_name = 'competitions';

$sql_list_name = "select name from `sports`";
$sql_insert; 


$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$link) {
	die('<p style="color:red">'.mysqli_connect_errno().' - '.mysqli_connect_error().'</p>');
}
try {
	$list_sports = array();
	$result = $link->query("SELECT * FROM `sports`");
	for ($i = 0; $i < $result->num_rows; $i++) 
		$list_sports[] = $result->fetch_row()[0];
		
	
	echo "<datalist id='list_sports'>";
	foreach ($list_sports as $sports) 
		echo "<option value = '". $sports ."'>". $sports ."</option>";
	echo "</datalist>";
	
}
catch (Exception $ex) {
	echo '<script>alert("Ошибка при работе с MySQL");</script>';
}


if (isset($_GET['but1'])){
	session_destroy();
	session_start();
}
?>

<form action="add_result.php" name="form1">
	<p class="title_sport">Выберите дисциплину<p>
	<p class="title_table">Предварительный список,добавляемых записей<p>
	<input class="input_sport" list='list_sports' name = 'input_sport' value="<?= $sport?>">
	<input class="but1" name="but1" type="submit" value="Выбор">
</form>

<form action="" name="form2">
<?php

if ($sport != htmlentities($_GET['input_sport'])){
	$sport = htmlentities($_GET['input_sport']);	
	$_SESSION['add_result']['sport'] = $sport;
} else {
	$sport = $_SESSION['add_result']['sport'];
}
try {	
	if (in_array($sport,$list_sports)){
		$sql_list_column = "select column_name,data_type "."from information_schema.columns "."where table_name = '".$sport."' and not column_name in ('id','added_by')";
		
		$result = $link->query($sql_list_column);
		
		for ($i = 0; $i < $result->num_rows; $i++) 
			$list_column[] = $result->fetch_row();
		
		$pattern = "";
		$invalid_massage = "";
		$name = "";
		echo $sql_insert;
		echo "<table class=\"table_input\" form='form2'>";
		echo "<tr> <th> ". $sport ." <th> </tr>";
		foreach ($list_column as $column){
			echo "<tr><td>";
			switch ($column[0]) {
				case 'id_part':
					echo "Номер участника";
					$pattern = "[0-9]{1,6}";
					$invalid_massage = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
				break;
				
				case 'attemps':
					echo "Число попыток";
					$pattern = "[0-9]{1,6}";
					$invalid_massage = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
				break;
				
				case 'result':
					echo "Результат";
					$pattern = "\[0-9]{1,}(\.\[0-9]{1,})?";
					$invalid_massage = "Вещественное число";
				break;

				case 'dist':
					echo "Дистанция";
					$pattern = "[0-9]{1,6}";
					$invalid_massage = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
				break;

				case 'time':
					echo "Время";
					$pattern = "([0-9]{1,}:[0-5][0-9]:)?[0-5][0-9](\.[0-9]{1,})?";
					$invalid_massage = "Форматы ввода времяни: чч:мм:сс.лл, чч:мм:сс, сс.лл";
				break;
				
				case 'round':
					echo "Стадия соревнования";
					$pattern = "([a-z]*[A-Z]*[0-9]*\s*)*";
					$invalid_massage = "Всё тлен";
				break;
					
				case 'score':
					echo "Счёт";
					$pattern = "[0-9]{1,9}:[0-9]{1,9}";
					$invalid_massage = "Счёт в формате [число:число]";
				break;
			}

				echo "</td> <td><input ";	
				echo " name='".$column[0]."' required oninput=\"setCustomValidity('')\" oninvalid=\"setCustomValidity('".$invalid_massage."')\" pattern=\"". $pattern ."\"></td></tr>";
		}
		echo "</table>";
		echo "<br><input name=\"but_out_2\" type=\"submit\" value='Сохранить запись в предворительном списке'>";

		echo "</form>";
		
		$id_ref = $_SESSION['login'];
		$list_value = array();
		$column = "";
		
		foreach ($list_column as $column) $list_value[$column[0]] = htmlentities($_GET[$column[0]]);
		
		if (!isset($_SESSION['add_result']['table_pole'])) $_SESSION['add_result']['table_pole'] = array();
		
		if (array_search(NULL,$list_value) === false){
			$_SESSION['add_result']['table_pole'][] = $list_value;
			$column_name = array();
			$pattern = array();
			$invalid_massage = array();
			
			foreach ($list_column as $column){
				switch ($column[0]) {
					case 'id_part':
						$column_name[$column[0]] = "Номер участника";
						$pattern[$column[0]] = "/^[0-9]{1,6}$/";
						$invalid_massage[$column[0]] = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
					break;
					
					case 'attemps':
						$column_name[$column[0]] = "Число попыток";
						$pattern[$column[0]] = "/^[0-9]{1,6}$/";
						$invalid_massage[$column[0]] = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
					break; 
					
					case 'result':
						$column_name[$column[0]] = "Результат";
						$pattern[$column[0]] = "/^\[0-9]{1,}(\.\[0-9]{1,})?$/";
						$invalid_massage[$column[0]] = "Вещественное число";
					break;

					case 'dist':
						$column_name[$column[0]] = "Дистанция";
						$pattern[$column[0]] = "/^[0-9]{1,6}$/";
						$invalid_massage[$column[0]] = "Неотрицательное, целое число,состоящее неболее чем из 6 цифр";
					break;

					case 'time':
						$column_name[$column[0]] = "Время";
						$pattern[$column[0]] = "/^([0-9]{1,}:[0-5][0-9]:)?[0-5][0-9](\.[0-9]{1,})?$/";
						$invalid_massage[$column[0]] = "Форматы ввода времяни: чч:мм:сс.лл, чч:мм:сс, сс.лл";
					break;
					
					case 'round':
						$column_name[$column[0]] = "Стадия соревнования";
						$pattern[$column[0]] = "/^([a-z]*[A-Z]*[0-9]*\s*)*$/";
						$invalid_massage[$column[0]] = "Всё тлен";
					break;
						 
					case 'score':
						$column_name[$column[0]] = "Счёт";
						$pattern[$column[0]] = "/^[0-9]{1,9}:[0-9]{1,9}$/";
						$invalid_massage[$column[0]] = "Счёт в формате [число:число]";
					break;
				}
			}
			$_SESSION['add_result']['column_name'] = $column_name;
			$_SESSION['add_result']['pattern'] = $pattern;
		}
	}
}
catch (Exception $ex) {
	echo '<script>alert("Ошибка при работе с MySQL");</script>';
}
?>

<form action="add_result.php" name="form3">

<?php
	$column_name = $_SESSION['add_result']['column_name'];
	$pattern = $_SESSION['add_result']['pattern'];
	if (count($_SESSION['add_result']['table_pole']) != 0){
		echo "<div form = \"form3\" class=\"div_table\"><table form = \"form3\" class=\"table_title\"><tr>";
		foreach ($list_column as $column) echo "<th>". $column_name[$column[0]] ."</th>";
		echo "<th>  </th>".
			 "</tr></table><div form = \"form3\" class=\"table_body\">".
			 "<table form = \"form3\" class=\"table_pole\">";
			 
		for ($i=0;$i<count($_SESSION['add_result']['table_pole']);$i++){
			$row = $_SESSION['add_result']['table_pole'][$i]; 
			$is_correct = true;
			foreach ($list_column as $column){
				$is_correct = $is_correct && preg_match($pattern[$column[0]],$row[$column[0]]);	
			}
			
			if ($is_correct){
				echo "<tr>";
				foreach ($list_column as $column){
					echo "<td>". $row[$column[0]] ."</td>";
				}
			
				echo "<td> <button class=\"button_del\" name=\"but_".$i."\"  value=\"X\">X</button> </td>";
				echo "</tr>";
			} else {
				unset($_SESSION['add_result']['table_pole'][$i]);
				$_SESSION['add_result']['table_pole'] = array_filter($_SESSION['add_result']['table_pole'], function($key) use ($i) {
					return $key != $i;
				}, ARRAY_FILTER_USE_KEY);
				
				$_SESSION['add_result']['table_pole'] = array_values($_SESSION['add_result']['table_pole']);
			}
		}
		echo "</table></div><input name=\"but_send\" type=\"submit\"></div>";
		 
		 
		 
		
		if (isset($_GET['but_send'])){				
			
			for ($i=0;$i<count($_SESSION['add_result']['table_pole']);$i++){
				
				$sql_insert = "insert into  `". $sport ."` ( `added_by` ";
				$column = "";
				
				foreach ($list_column as $column){ 
					$list_value[] = $_SESSION['add_result']['table_pole'];
					$sql_insert = $sql_insert.", `". $column[0] ."` ";
				}
				
				$sql_insert = $sql_insert.") value ( '".$id_ref."' ";
				
				$row = $_SESSION['add_result']['table_pole'][$i];
				$key_col = array_keys($list_column);
				foreach ($row as $value){
					$sql_insert = $sql_insert.", '". $value ."' ";
				}
				$sql_insert = $sql_insert." )";
			
				if ($is_correct)
					$link->query($sql_insert);
			}
			
			unset($_SESSION['add_result']['table_pole']);
		}
	}
?>
</form>