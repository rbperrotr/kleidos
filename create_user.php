﻿<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - Sign up</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		<?php
			require('controler/bdd.php');
			require('controler/global.php');
			/* require('model/user.php');
			require('controler/user.php');
			if(checkLogin() == false)
			{
				includeBanner();
			}
			else
			{
				header("Location: index.php");
				exit;
			}*/
			includeBannerCreateUser();
		?>
		<h1 class="formtitle">Create an account</h1>
		<form action="index.php" method="post">
			<div class="formtextinputlabel">Email address*</div><input class="formtextinput" type="text" name="email">  You will get a confirmation of registration by email.<br>
			<div class="formtextinputlabel">First name*</div><input class="formtextinput" type="text" name="fname"><br>
			<div class="formtextinputlabel">Last name*</div><input class="formtextinput" type="text" name="lname"><br>
			<div class="formtextinputlabel">Create password*</div><input class="formtextinput" type="password" name="pwd"><span class="formtextinputlabel">  Re-enter password*</span><input class="formtextinput" type="password" name="pwd2"><br>
			<!--<div class="formtextinputlabel">Safe ID*</div><input class="formtextinput" type="text" name="safe"><br>-->
			<br>
			<input type="hidden" name="action" value="create_user"/>
			<input class="stdButton" type="submit" value="Submit"/>
			<!--
			<button class="stdButton" name="buttonclicked" type="submit" value="value_Submit">Submit2</button>
			<button class="stdButton" name="buttonclicked" type="submit" value="value_Cancel">Cancel2</button>

			<input type="hidden" name="action" value="email_notif" />
			<input class="stdButton" type="submit" value="Submit"/>
			-->
		</form>
		<br>
		* marks required fields
		<br>
		Please note that you are expected to sign up with your Thomson Reuters email address.<br>
		All accounts not compliant with those rules will be deleted. <br>
		This game is primary targeting Unified Platform Group, if you are not part of UPG, you can request an account by contactint the Gardians. 
		If you have trouble signing in, please contact the <a href="mailto: guardians@kleidos.tk">guardians</a>.<br>
		<?php
				includeFooter();
		?>
	</body>
</html>