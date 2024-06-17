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
	<p><input type="submit" value="Log" name="done"></p>
	<p><input type="submit" value="reg" name="Reg"></p>
	<button name="Url">Url</button><br>
	</form>
   </body>
</html>
<?php
	session_start();
	if (isset($_POST['Reg']))
{
	echo '123';
	header('Location: http://localhost/reg.php');
}
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
	$log=$_POST['login'];
	$pass=$_POST['pass'];
	$sql = mysqli_query($link, "SELECT `id` FROM `data` WHERE ('$log'=login AND MD5('$pass')=pass) ");
	  if ($sql) 
		{
			if (mysqli_num_rows($sql)<>0)
				{
					$res=$sql->fetch_array();
					$_SESSION['userid']=$res[0];
					header('Location: send.php');
				}
			else {
				echo 'Incorrect login or password';
			}
		} 
	else
		{
		echo '<p>Error: ' . mysqli_error($link) . '</p>';
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
if (isset($_POST['Url']))
{
	header('Location:  http://localhost/url.php');
}
?>