<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Form</title>
  </head>
  <body>
  	<form action="" method="post"  enctype="multipart/form-data">
	<input type='file' name='file[]' class='file-drop' id='file-drop' multiple><br>
	<input type='submit' value='Upload' name ="load"><br>
	<button name="TxtWork">TxtOut</button>
	<button name="CsvWork">CsvOut</button><br>
	<table>

<?php
session_start();
$host = 'localhost';  
$user = 'root';   
$pass = ''; 
$db_name = 'practic';   
$link = mysqli_connect($host, $user, $pass, $db_name); 
$uploadDir = "files/"; 
if (isset($_POST['load']))
{
$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc', 'docx','csv');
$count=count($_FILES['file']['name']);
	for($i = 0; $i < count($_FILES['file']['name']); $i++) 
		{ 
			$userid=$_SESSION['userid'];
			$username=mysqli_query($link, "SELECT `login` FROM `data` WHERE id=$userid");
			$username=$username->fetch_array();
			$username=$username[0];
			$fileName = $_FILES['file']['name'][$i];
			$fileNameCmps = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameCmps));
			echo $_FILES['file']['name'][$i]." ";
			if (in_array($fileExtension, $allowedfileExtensions)) 
			{
				{ 
					$uploadFile[$i] = $uploadDir . basename($_FILES['file']['name'][$i]);
					if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadFile[$i])) 
						{
							echo "Uploaded by user ".$username."<br>";
						}
					else 
						{
							echo "Error ".$_FILES['file']['error'][$i]."<br>";
						}
				}
			}
			else 
			{
				echo "Incorrect <br>";
			}
			
		}
	
} 
if (isset($_POST['TxtWork']))
{
	$txt=False;
	$files=scandir($uploadDir);
	if (count($files)>2)
	{		
		for($i = 0; $i < count($files); $i++) 
			{ 
				
				$fileName = $files[$i];
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));
				if ($fileExtension =='txt') 
				{
					$txt=True;
					$handle = fopen($uploadDir."/".$fileName, "r");
					$content = fread($handle, filesize($uploadDir."/".$fileName));
					echo $fileName." content: ".$content."<br>";
					fclose($handle);
				}
				else
				{
					if (($i==count($files)-1)&& (!$txt))
					{
						echo 'Missing txt';
					}
				}
			
			}
	}
	else
	{
		echo 'Missing files';
	}
}
if (isset($_POST['CsvWork']))
{
	$csv=False;
	$csvs=scandir($uploadDir);
	if (count($csvs)>2)
	{
		for($i = 0; $i < count($csvs); $i++) 
		{
			$csvName = $csvs[$i];
			$csvNameCmps = explode(".", $csvName);
			$csvExtension = strtolower(end($csvNameCmps));
			if ($csvExtension =='csv') 
			{
				$csv=True;
				if (($handle = fopen($uploadDir."/".$csvName, "r")) !== FALSE) 
				{
				$tempcount=0;
				while (($data = fgetcsv($handle,500,',','\\')) !== FALSE)
					{
						$num = count($data);
						if ($tempcount==0)
						{
							$dataheader=$data;
							?>
							<tr>
							<?php
							for ($c=0; $c<$num;$c++)
							{	$dataheader[$c]=str_replace('"','',$dataheader[$c]);	
							?>
								<th><?php echo $dataheader[$c]?></th>
							<?php
							}
							?>
							</tr>
							<?php
							$tempcount++;
						}
						else
						{
							?>
							<tr>
							<?php
						for ($c=0; $c < $num; $c++) 
							{
								$data[$c]=str_replace('"','',$data[$c]);
								?>
								<td> <?php echo $data[$c]?></td>
							<?php
							}
							?>
							</tr>
							<?php
						}
					}
				}
			}	
			else
			{
				if (($i==count($csvs)-1)&& (!$csv))
				{
					echo 'Missing csv';
				}
			}
		}
	}
	else
	{
		echo 'Missing files';
	}
}

$link->close();
?>
	</table>
	</form>
   </body>
</html>