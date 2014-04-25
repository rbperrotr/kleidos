<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - Administration - Answers</title>
		<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		
		<?php
			require('../controler/global.php');
			
			if(checkLogin() == true)
			{
				includeBanner();
			}
			else
			{
				header("Location: index.php");
				exit;
			}
		?>
		<h2>Answers</h2>
			<p>Number of answers = 
			<?php 
				//require('controler/bdd.php');
				//require('controler/admin.php');
				//echo admin_getnbusers($bdd)-3;
				echo " Coming soon...";
			?> 
			</p>
			<p>List of answers
			<?php
				require('model/answer.php');
				
				$answers = admin_getallanswers($bdd);
				
				echo ("<table style=\"width:600px\">");
					foreach ($answers as $ananswer)
					{
						echo ("<tr><td>".$ananswer->getenigmaid()."</td><td>".$ananswer->getfullname()."</td><td>".$ananswer->getText()."</td><td>".$ananswer->getdate_time()."</tr>");
					}
				
			?>
			</p>
	</body>