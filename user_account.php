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
			
			includeBanner();
			
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
			}
		?>
		<h1>My account</h1>
			
		<h2>My email notification preference</h2>	
		<form method="post" action="user_account.php">
			<?php
				$currentEmailNotif = user_getEmailFrequency($bdd, $_SESSION['uid']);
				echo_debug("USER ACCOUNT | $currentEmailNotif=".$currentEmailNotif."<br>");
			?>
			Select below your preferred frequency to receive your Kleidos status.<br>
			<select name="EmailFrequency" class="stdButton" >
				  <option value="Daily" <?php if($currentEmailNotif=='Daily') echo("selected=\"selected\""); ?>>Daily</option>
				  <option value="Weekly" <?php if($currentEmailNotif=='Weekly') echo("selected=\"selected\""); ?>>Weekly</option>
				  <option value="None" <?php if($currentEmailNotif=='None') echo("selected=\"selected\""); ?>>None</option>
			</select>
			
			<input type="hidden" name="action" value="email_notif_pref" />
			<input class="stdButton" type="submit" value="Save"/>
		</form>
			
		<h2>My codes</h2>		
		<br>
		Below are the codes you already won which are still available.<br>
		Remember that those codes are private, then only you can use them.<br>
		<p><strong>
		<?php
			//list all codes available
			$codeText = code_assignedcodes($bdd, $_SESSION['uid']);
			
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