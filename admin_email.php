<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - Administration - Send email to all players</title>
		<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>

	<body>
	
	<script type="text/javascript">
	function unhide(divID) {
		var item = document.getElementById(divID);
		if (item) {
		  item.className=(item.className=='admHidden')?'admUnhidden':'admHidden';
		}
	}
	</script>
	
		<?php
			require('controler/bdd.php');
			require('controler/global.php');
			require('controler/admin.php');
			$bcc = "";

			if(checkLogin() == true)
			{
				includeBanner();
			}
			else
			{
				header("Location: index.php");
				exit;
			}
			
			if($_SESSION['uid']!=3 and $_SESSION['uid']!=4 and $_SESSION['uid']!=5)
			{
				session_destroy();
				header("Location: index.php");
				exit;
			}
			
			if(isset($_POST['action']))
			{
				/* localhost do not allow to test emails as there is no mail server installed
				email features need to be tested on a real server
				when testing on a real server there are real data
				The flag allow to decide if we are in production on not
				By default set flag to 1 then the flag is reviewed according to the */
				$_SESSION['TESTFLAG']=1;
				
				//Set TESTFLAG when opening the form
				if(htmlspecialchars($_POST['action']) == "open_email_allplayers")
				{
					$_SESSION['TESTFLAG']=0;  
				}
				elseif(htmlspecialchars($_POST['action']) == "open_email_test")
				{
					$_SESSION['TESTFLAG']=1;  
				}
			}
			

			echo ("<p><h2>Use this page to send an email to all players</h2><br>");
			echo_debug("ADMIN EMAIL | TESTFLAG ".$_SESSION['TESTFLAG']);
			//if not in test mode display a warning and list of email addresses
			if($_SESSION['TESTFLAG']==0)
			{
				echo "<div style=\"color:yellow\">Test mode is OFF. Your email will be sent to all players.</div>";
				$emails = admin_getallemails($bdd);
				echo('<br><a href="javascript:unhide(\'UsersAllEmails\');">Display/hide bcc distribution list</a><br>');
				echo('<div id="UsersAllEmails" class="admHidden">');
				echo('<table style="width:800px" class="bluetext"><tr><td>');
				foreach ($emails as $anemail)
				{
					echo ("".$anemail."; ");
				}
				echo('</td></tr></table></div>');
			}
			else
			{
				echo "<div>Test mode is ON</div>";
			}
			
			echo('');
		?>
		<form method="post" action="admin_email_result.php">
			<div class="formtextinputlabel">Subject*</div>
			<input class="formtextinputlabel" type="text" name="admin_email_subject"><br>
			<div class="formtextinputlabel">Message*</div>
			<textarea class="formtextareainput" name="admin_email_message"></textarea>
			<br>
			An automatic signature will be added<br>
			<?php
			if(htmlspecialchars($_POST['action']) == "open_email_allplayers")
				echo ('<input type="hidden" name="action" value="send_email_allplayers" />');
			elseif(htmlspecialchars($_POST['action']) == "open_email_test")
				echo ('<input type="hidden" name="action" value="send_email_test" />');
			else
				echo ('<input type="hidden" name="action" value="admin_email_notset" />');
			?>
			<input class="stdButton" type="submit" value="Send"/>
		</form>
		</p>

		<?php
			includeFooter();
		?>
	</body>
</html>