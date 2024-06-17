<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Form</title>
  </head>
  <body>
    <form action="" method="post">
	<p>Enter url: <input type="text" name="url"></p>
	<p><input type="submit" value="Get token" name="get_token"></p>
	</form>
   </body>
</html>
<?php
require 'C:\practic\php\vendor\autoload.php';
use Sqids\Sqids;
	$sqids = new Sqids(minLength: 6);
	$host = 'localhost'; 
    $user = 'root';    
    $pass = ''; 
    $db_name = 'practic'; 
    $link = mysqli_connect($host, $user, $pass, $db_name); 
	if (isset($_POST['get_token']))
	{
		$url=$_POST['url'];
		if (filter_var($url, FILTER_VALIDATE_URL) == FALSE) 
		{
			echo 'Not a valid URL';
		}
		else
		{
			$url_check=mysqli_query($link, "SELECT `id` FROM `short_url` WHERE (url='$url') ");
			$url_check=$url_check->fetch_array();
			if(empty($url_check))
			{
				$id=mysqli_query($link, "SELECT MAX(id) FROM `short_url`");
				$id=$id->fetch_array();
				if($id[0]==null)
				{
					$id=1;
					$id=array($id);
					$token = $sqids->encode($id);
					$sql=mysqli_query($link, "INSERT INTO `short_url` (`url`,`token`) VALUES ('$url', '$token') ");
					if (!$sql)
					{
						echo '<p>Error: ' . mysqli_error($link) . '</p>';
					}
				}
				else
				{
				
					$id=$id[0];
					$id++;
					$id=array($id);
					$token = $sqids->encode($id);
					$sql=mysqli_query($link, "INSERT INTO `short_url` (`url`,`token`) VALUES ('$url', '$token') ");
					if (!$sql)
					{
						echo '<p>Error: ' . mysqli_error($link) . '</p>';
					}
				}
			}
			$token=mysqli_query($link, "SELECT `token` FROM `short_url` WHERE url='$url'");
			if (!$token)
			{
				echo '<p>Error: ' . mysqli_error($link) . '</p>';	
			}
			else
			{
			$token=$token->fetch_array();
			echo 'Short url' ;
			?> <a href=<?php echo 'http://localhost/'.$token[0]; ?> target="_blank"><?php echo 'http://localhost/'.$token[0]?></a><?php	
			}
		}
	}		

	$link->close();

?>