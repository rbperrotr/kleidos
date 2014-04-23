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
				<h2> Manage DEBUG mode</h2>
				<p>Debug mode allows debugging by displaying trace on the pages - don't keep it on</p>
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
					<input type="hidden" name="action" value="debug_mode" />
					<input class="stdButton" type="submit" value="Submit"/>
				</form>
				</p>
				<h2> Manage Email notification</h2>
				<p>Desactivate Email notification when using in localhost</p>
				<p>
				<form method="post" action="index.php">
					<p>
					<?php
						if(isset($_SESSION['EMAILNOTIF']))
						{
							if($_SESSION['EMAILNOTIF']==1)
							{
								echo "<input type=\"checkbox\" name=\"checkbox_email\" id=\"checkbox_email\" checked=\"checked\"/> <label for=\"checkbox_email\">Email notification</label>";
							}
							else{
								echo "<input type=\"checkbox\" name=\"checkbox_email\" id=\"checkbox_email\"/> <label for=\"checkbox_email\">Email notification</label>";
							}
						}
						else
						{
							$_SESSION['EMAILNOTIF']=0;
							echo "<input type=\"checkbox\" name=\"checkbox_email\" id=\"checkbox_email\"/> <label for=\"checkbox_email\">Email notification</label>";
						}
					?>
					</p>
					<input type="hidden" name="action" value="email_notif" />
					<input class="stdButton" type="submit" value="Submit"/>
				</form>
				</p>				
				<br>
				<h2>Sign in page</h2>
				<p><a href="create_user.php">Sign up!</a></p>
				<br>
				<h2>Informations to connect to the database</h2>
				<p>
				<a href="http://members.000webhost.com/login.php">Connect to the server administration console</a><br>
				Login: rbperrotr@gmail.com<br>
				password = kleidos2014<br>
				</p>
			</form>
			<?php
				includeFooter();
			?>
	</body>
</html>