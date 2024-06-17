
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Form</title>
  </head>
  <body>
    <form action="" method="post">
	<p>Enter login: <input type="text" name="login"></p>
	<p>Enter password: <input type="password" name="pass"></p>
	<p><input type="submit" value="Reg" name="done"></p>
	</form>
   </body>
</html>

<?php
    $host = 'localhost';  
    $user = 'root';  
    $pass = ''; 
    $db_name = 'practic';   
    $link = mysqli_connect($host, $user, $pass, $db_name); 

    if (!$link) {
      echo 'Erorr!: ' . mysqli_connect_errno() . 'code:' . mysqli_connect_error();
      exit;
    }

if (isset($_POST['done'])) 
{	

	if (!empty($_POST['login']) && !empty($_POST['pass']))
	{
		try
		{
			$sql = mysqli_query($link, "INSERT INTO `data` (`login`, `pass`) VALUES ('{$_POST['login']}', MD5('{$_POST['pass']}'))");
			 if ($sql) 
				{
					header('Location: index.php');
				} 
			
		}
		catch (Exception $e)
		{
			echo 'Login is already used';
		}
	}
	else
	{
		if ($_POST['login'] == '') 
		{
			echo 'Login is empty!<br>';
		}	
		if (empty($_POST['pass'])) 
		{
		echo "Password is empty!<br>";
		}
	}
	$link->close();
}

?>