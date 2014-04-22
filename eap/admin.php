<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - Administration</title>
		<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		<?php
			//require('controler/bdd.php');
			require('controler/global.php');
			//require('model/user.php');
			//require('controler/user.php');
			//require('model/enigma.php');
			//require('controler/enigma.php');
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
			
				<p>
				<form method="post" action="index.php">
					<p>
					<?php
						if(isset($_SESSION['DEBUG']))
						{
							if($_SESSION['DEBUG']==1)
							{
								echo "<input type=\"checkbox\" name=\"checkbox_debug\" id=\"checkbox_debug\" checked=\"checked\"/> <label for=\"checkbox_debug\">Debug mode</label>";
							}
							else{
								echo "<input type=\"checkbox\" name=\"checkbox_debug\" id=\"checkbox_debug\"/> <label for=\"checkbox_debug\">Debug mode</label>";
							}
						}
						else
						{
							$_SESSION['DEBUG']=0;
							echo "<input type=\"checkbox\" name=\"checkbox_debug\" id=\"checkbox_debug\"/> <label for=\"checkbox_debug\">Debug mode</label>";
						}
					?>
					</p>
					<input type="hidden" name="action" value="admin" />
					<input class="stdButton" type="submit" value="Submit"/>
				</form>
				</p>
			</form>
	</body>
</html>