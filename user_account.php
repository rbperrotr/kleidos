<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - My account</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		<?php
			require('controler/bdd.php');
			require('controler/global.php');
			require('controler/code.php');
			require('controler/user.php');
			
			
			if(checkLogin() == true)
			{
				includeBanner();
				$userID=$_SESSION['uid'];
			}
			else
			{
				header("Location: index.php");
				exit;
			}
			
			
			if(isset($_POST['action']))
			{
				if(htmlspecialchars($_POST['action']) == "email_notif_pref")
				{
					if(isset($_POST['EmailFrequency']))
					{
						$newEmailFrequency = htmlspecialchars($_POST['EmailFrequency']);
						$userID = $_SESSION['uid'];
						echo_debug("USER ACCOUNT | newEmailFrequency=".$newEmailFrequency." for userID=".$userID."<br>");
						try
						{
							$query = $bdd->query("UPDATE user SET emailNotif=\"".$newEmailFrequency."\" WHERE id=".$userID);
							$currentEmailNotif = $newEmailFrequency;
							echo ("Your preferences have been saved.");
						}
						catch (Exception $e)
						{
							die('Error : '.$e->getMessage());
						}
						
					}
				}
				elseif(htmlspecialchars($_POST['action']) == "email_other_players")
				{
					if(isset($_POST['EmailOtherPlayers']))
					{
						$newEmailOtherPlayers = htmlspecialchars($_POST['EmailOtherPlayers']);
						$userID = $_SESSION['uid'];
						echo_debug("USER ACCOUNT | newEmailOtherPlayers=".$newEmailOtherPlayers." for userID=".$userID."<br>");
						try
						{
							$query = $bdd->query("UPDATE user SET emailOtherPlayers=\"".$newEmailOtherPlayers."\" WHERE id=".$userID);
							$currentEmailOtherPlayers = $newEmailOtherPlayers;
							echo ("Your preferences have been saved.");
						}
						catch (Exception $e)
						{
							die('Error : '.$e->getMessage());
						}
						
					}
				}
				elseif(htmlspecialchars($_POST['action']) == "change_password")
				{
					if(isset($_POST['pwd']) && isset($_POST['pwd2']))
					{
						$pwd = htmlspecialchars($_POST['pwd']);
						$pwd2 = htmlspecialchars($_POST['pwd2']);
						if($pwd=="")
						{
							echo_debug("USER ACCOUNT | Change password blank pwd check NOT PASSED<br>");
							echo '<div class=\"error_message\"><strong>Password unchanged, please fill-in password field.</strong></div>';
						}
						elseif($pwd2=="")
						{
							echo_debug("USER ACCOUNT | Change password blank pwd heck NOT PASSED<br>");
							echo '<div class=\"error_message\"><strong>Password unchanged, please retype password.</strong></div>';
						}
						elseif( $pwd != $pwd2)
						{
							echo_debug("USER ACCOUNT | Change password pwd check > pwd <> pw2<br>");
							echo '<div class=\"error_message\"><strong>Password unchanged, please make sure passwords are identical.</strong></div>';
						}
						else
						{
							$pwd = md5(htmlspecialchars($_POST['pwd']));
							$pwd2 = md5(htmlspecialchars($_POST['pwd2']));
							user_changePassword($bdd, $userID, $pwd);
							echo_debug ("USER ACCOUNT | Change password: after password saved.");
						}
					}
				}
			}
		?>
		<h1>My account</h1>
			
		<h2>My preferences</h2>
		<h3>My email notification preference</h3>	
		<form method="post" action="user_account.php">
			<?php
				$currentEmailNotif = user_getEmailFrequency($bdd, $_SESSION['uid']);
				echo_debug("USER ACCOUNT | $currentEmailNotif=".$currentEmailNotif."<br>");
			?>
			Select below your preferred frequency to receive your Kleidos status.<br>
			Please note that automatic notifications are not yet activated.<br>
			<select name="EmailFrequency" class="stdButton" >
				  <option value="Daily" <?php if($currentEmailNotif=='Daily') echo("selected=\"selected\""); ?>>Daily</option>
				  <option value="Weekly" <?php if($currentEmailNotif=='Weekly') echo("selected=\"selected\""); ?>>Weekly</option>
				  <option value="None" <?php if($currentEmailNotif=='None') echo("selected=\"selected\""); ?>>None</option>
			</select>
			
			<input type="hidden" name="action" value="email_notif_pref" />
			<input class="stdButton" type="submit" value="Save"/>
		</form>
		<h3>I accept to receive emails from other players</h3>	
		<form method="post" action="user_account.php">
			<?php
				$currentEmailOtherPlayers = user_getEmailOtherPlayers($bdd, $_SESSION['uid']);
				echo_debug("USER ACCOUNT | $currentEmailOtherPlayers=".$currentEmailOtherPlayers."<br>");
			?>
			To help enigma resolution and progress on Kleidos other players can share with you hints or questions.<br>
			Please note that this feature will be activated soon.<br>
			<select name="EmailOtherPlayers" class="stdButton" >
				  <option value="Yes" <?php if($currentEmailOtherPlayers=='Yes') echo("selected=\"selected\""); ?>>Yes</option>
				  <option value="No" <?php if($currentEmailOtherPlayers=='No') echo("selected=\"selected\""); ?>>No</option>
			</select>
			
			<input type="hidden" name="action" value="email_other_players" />
			<input class="stdButton" type="submit" value="Save"/>
		</form>
		
		<h2> My password</h2>
		<form method="post" action="user_account.php">
			<?php
				$currentPwd = user_getEmailFrequency($bdd, $_SESSION['uid']);
				echo_debug("USER ACCOUNT | currentPwd=".$currentPwd."<br>");
			?>
			To change your password, enter the current and a new one.<br>
			<div class="formtextinputlabel">New password*</div>
			<input class="formtextinput" type="password" name="pwd"><br>
			<div class="formtextinputlabel">Re-enter password*</div>
			<input class="formtextinput" type="password" name="pwd2"><br>
			
			<input type="hidden" name="action" value="change_password" />
			<input class="stdButton" type="submit" value="Confirm"/>
		</form>
		
		<h2>My codes</h2>		
		<br>
		Below are the codes you already won which are still available.<br>
		Remember that those codes are private, then only you can use them.<br>
		<p><strong>
		<?php
			//list all codes available
			$codeText = code_assignedCodesByUser($bdd, $_SESSION['uid']);
			
			foreach ($codeText as $code)
				{
					echo $code." ";
				}
		?>		
		</strong></p>
		<?php
				includeFooter();
		?>
	</body>
</html>