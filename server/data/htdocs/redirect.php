<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Form</title>
  </head>
  <body>
    <form action="" method="post">
	</form>
   </body>
</html>

<?php
	$host = 'localhost'; 
    $user = 'root';  
    $pass = ''; 
    $db_name = 'practic';   
    $link = mysqli_connect($host, $user, $pass, $db_name); 
	$token=substr($_SERVER["REQUEST_URI"],1);
	$new_url=mysqli_query($link, "SELECT `url` FROM `short_url` WHERE token='$token'");
	if ($new_url)
	{
		$new_url=$new_url->fetch_array();
		if(!empty($new_url))
		{
			$new_url=$new_url[0];
			header('Location: ' . $new_url);
		}	
	}
	echo 'This url not exist';
	$link->close();
?>