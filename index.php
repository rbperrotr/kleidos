<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos</title>
		<meta http-equiv="Content-Type" content="text/html; charset="utf-8"; />
		<link rel="stylesheet" href="style.css" />
    </head>

	<body>
		<div id="wrapper">
			<header>
				<section class="centered">
				<?php
					require('controler/bdd.php');
					require('controler/global.php');
					require('model/user.php');
					require('controler/user.php');
					require('model/truser.php');
					require('controler/truser.php');
					
					$_SESSION['EMAILNOTIF']=0;
					$_SESSION['DEBUG']=1; // to comment in normal use
					$login = false;
					echo_debug("INDEX | Starting<br>");
					if(isset($_POST['action']))
					{
						echo_debug("INDEX | go action script<br>");
						if(htmlspecialchars($_POST['action']) == "logout")
						{
							session_destroy();
							header("Location: index.php");
							exit;
						}	
						elseif(htmlspecialchars($_POST['action']) == "login")
						{
							if(isset($_POST['login']) && isset($_POST['password']))
							{
								$user = user_confirmPassword($bdd, htmlspecialchars($_POST['login']), md5(htmlspecialchars($_POST['password'])));
								if($user != false)
								{
									$_SESSION['user'] = $user;
									$_SESSION['login'] = $user->getFirstName();
									$_SESSION['uid'] = $user->getId();
								}
							}
						}
						elseif(htmlspecialchars($_POST['action']) == "debug_mode")
						{
							if(isset($_POST['checkbox_debug']))
							{
								echo_debug("debug goes to ON<br/>");
								$_SESSION['DEBUG']=1;								
							}
							else
							{
								echo_debug("debug goes to OFF<br/>");
								$_SESSION['DEBUG']=0;
							}
						}
						elseif(htmlspecialchars($_POST['action']) == "email_notif")
						{
							if(isset($_POST['checkbox_email']))
							{
								echo_debug("email notification goes to ON<br/>");
								$_SESSION['EMAILNOTIF']=1;								
							}
							else
							{
								echo_debug("email notification goes to OFF<br/>");
								$_SESSION['EMAILNOTIF']=0;
							}
						}
						elseif(htmlspecialchars($_POST['action']) == "create_user")
						{
							echo_debug("INDEX | go create_user script<br>");

							if(isset($_POST['email']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['safe']))
							{
								$login = htmlspecialchars($_POST['email']);
								$fname = htmlspecialchars($_POST['fname']);
								$lname = htmlspecialchars($_POST['lname']);
								$pwd = htmlspecialchars($_POST['pwd']);
								$pwd2 = htmlspecialchars($_POST['pwd2']);
								$safe = htmlspecialchars($_POST['safe']);
								
								echo_debug("INDEX | before pwd check<br>");
								
								if($login=="")
								{
									echo_debug("INDEX | blank login/email check NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please fill-in email field.</strong></div>';
								}
								elseif($fname=="")
								{
									echo_debug("INDEX | firstname check NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please fill-in first name field.</strong></div>';
								}
								elseif($lname=="")
								{
									echo_debug("INDEX | lastname check NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please fill-in last name field.</strong></div>';
								}
								elseif($pwd=="")
								{
									echo_debug("INDEX | blank pwd heck NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please fill-in password field.</strong></div>';
								}
								elseif($pwd2=="")
								{
									echo_debug("INDEX | blank pwd heck NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please retype password.</strong></div>';
								}
								elseif($safe=="")
								{
									echo_debug("INDEX | lastname check NOT PASSED<br>");
									echo '<div class=\"error_message\"><strong>Account not created, please fill-in Safe ID field.</strong></div>';
								}
								elseif( $pwd != $pwd2)
								{
									echo_debug("INDEX | pwd check > pwd <> pw2<br>");
									echo 'Account not created, please make sure passwords are identical.';
								}
								elseif(!strrpos($login, '@thomsonreuters.com'))
								{
									echo_debug("INDEX | @thomsonreuters.com check : NOT PASSED<br>");
									echo 'Account not created, a Thomson Reuters valid email is required.';
								}
								elseif(!truser_check_email_safeID_compliance($bdd, $login, $safe))
								{
									echo_debug("INDEX | email and safeID check: NOT PASSED<br>");
									echo "<div class=\"error_message\"><strong>Account not created, please check email and safeID</strong></div>";
								}
								else
								{
									echo_debug("INDEX | email and safeID check: PASSED, then create user<br>");
									
									$pwd = md5(htmlspecialchars($_POST['pwd']));
									$pwd2 = md5(htmlspecialchars($_POST['pwd2']));
									user_addUser($bdd, $login, $fname, $lname, $pwd, $safe);
									$user = user_confirmPassword($bdd, $login, $pwd);
									if($user != false) 
									{
										$_SESSION['user'] = $user;
										$_SESSION['login'] = $user->getFirstName();
										$_SESSION['uid'] = $user->getId();
									}
									else
									{
										echo_debug("INDEX | WRONG PASSWORD ENTERED<br>");
										echo "<p><strong>Wrong password, please try again.</strong></p>";
										exit;
									}
									echo "<div class=\"error_message\"><strong>Account created for ".$fname." ".$lname.".</strong></div>";
								}
							}
						}
					}
					else
					{
						echo_debug("INDEX | no action");
					}
					
					includeBanner();
				?>
				</section>
			</header>
			<?php
				includeIndexContent();
			?>
		</div>		
		<?php
				includeFooter();
		?>
		
	</body>
</html>