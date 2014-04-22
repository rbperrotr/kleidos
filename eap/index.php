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
					
					$login = false;
					if(isset($_POST['action']))
					{
						//echo "<script type='text/javascript'>alert('go action script');</script>";
						if(htmlspecialchars($_POST['action']) == "logout")
						{
							session_destroy();
							header("Location: index.php");
							exit;
						}
						elseif(htmlspecialchars($_POST['action']) == "create_user")
						{
							//echo "<script type='text/javascript'>alert('go create_user script');</script>";

							if(isset($_POST['email']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['safe']))
							{
								$login = htmlspecialchars($_POST['email']);
								$fname = htmlspecialchars($_POST['fname']);
								$lname = htmlspecialchars($_POST['lname']);
								$pwd = md5(htmlspecialchars($_POST['pwd']));
								$pwd2 = md5(htmlspecialchars($_POST['pwd2']));
								$safe = htmlspecialchars($_POST['safe']);
								
								//echo "<script type='text/javascript'>alert('before pwd check');</script>";
								
								if( $pwd != $pwd2)
								{
									echo 'Account not created, please make sure passwords are identical.';
									session_destroy();
									header("Location: index.php");
									exit;
								}
								elseif(!strrpos($login, '@thomsonreuters.com'))
								{
									echo 'Account not created, a Thomson Reuters valid email is required.';
									session_destroy();
									header("Location: index.php");
									exit;
								}
								elseif(!truser_check_email_safeID_compliance($bdd, $login, $safe))
								{
									//echo "<script type='text/javascript'>alert('email safeID check NOT PASSED');</script>";
									echo "<div class=\"error_message\"><strong>Account not created, please check email and safeID</strong></div>";
									session_destroy();
									header("Location: index.php");
									exit;
								}
								else
								{
									//echo "<script type='text/javascript'>alert('email safeID check passed');</script>";
									user_addUser($bdd, $login, $fname, $lname, $pwd, $safe);
									$user = user_confirmPassword($bdd, $login, $pwd);
									if($user != false) 
									{
										$_SESSION['user'] = $user;
										$_SESSION['login'] = $user->getFirstName();
										$_SESSION['uid'] = $user->getId();
									}
								}
							}
							
								
								/*
								else
								{	
									echo "<script type='text/javascript'>alert('not Value Submit');</script>";
									session_destroy();
									header("Location: index.php");
									exit;
								}
							}
							else
							{
								echo "<script type='text/javascript'>alert('button clicked unavailable');</script>";
								session_destroy();
								header("Location: index.php");
								exit;							
							}*/
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
						elseif(htmlspecialchars($_POST['action']) == "admin")
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