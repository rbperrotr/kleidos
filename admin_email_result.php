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
				if(htmlspecialchars($_POST['action']) == "send_email_allplayers" || htmlspecialchars($_POST['action']) == "send_email_test")
				//if post send email test or real email
				{
					//check subject and message are set 
					if(isset($_POST['admin_email_subject']) && isset($_POST['admin_email_message']))
					{
						$admin_email_subject = htmlspecialchars($_POST['admin_email_subject']);
						$admin_email_message = nl2br(htmlspecialchars($_POST['admin_email_message']));
						echo_debug ("ADMIN EMAIL | subject: ".$admin_email_subject.".<br>");
						echo_debug ("ADMIN EMAIL | message _______________________________________________________________ <br>");
						echo_debug ($admin_email_message);
						echo_debug ("ADMIN EMAIL | message _______________________________________________________________ <br>");
						
						//check subject and message not empty else return error
						if($admin_email_subject == "")
						{	
							echo ("<strong>Message not send. Enter a subject</strong><br>");
						}
						elseif($admin_email_message == "")
						{
							echo ("<strong>Message not send. Enter a message</strong><br>");
						}
						else
						{
							//prepare email
							$new_line = "\r\n";
							$boundary = "-----=".md5(rand());
							$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
							$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
							//prepare distribution list
							$AllEmails = admin_getallemails($bdd); //to be added as bcc if not TEST MODE
							$to = "guardians@kleidos.tk";
							
							if(!$_SESSION['TESTFLAG']==1) 
							{
								echo_debug ("ADMIN EMAIL | TEST MODE build bcc list");
								foreach ($AllEmails as $anemail)
								{	
									$bcc.= $anemail.", ";
								}
								$header.= "Bcc: ".$bcc.$new_line;
							}
							else
							{
								echo_debug("ADMIN EMAIL | TEST MODE Header do not contain BCC as TEST FLAG is ON<br>");
							}
							$header.= "MIME-Version: 1.0".$new_line;
							$header.= "Content-Type: text/html; charset=ISO-8859-1".$new_line." boundary=\"$boundary\"".$new_line;			
							
							$subject="[Kleidos: Message to all players] ".$admin_email_subject;
							$now=date('Y-m-d H:i:s');
							
							//prepare HTML message with a 600px table, a header row, a message row including a signature and a footer row with link to kleidos
							$message='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" ><head><meta http-equiv="Content-Type" content="text/html; charset="utf-8"; /><link rel="stylesheet" href="http://www.kleidos.tk/style.css" /></head>'.$new_line;
							$message.='<body><table width=600px style="border-top: 2px solid #FF8000; border-bottom: 2px solid #FF8000; padding:7px;"><tr>	<td style="text-align:left; width=500px;"><strong>Message from the Guardians of Kleidos</strong></td><td><img src="http://www.kleidos.tk/resources/small logo.png" height="55"/></td></tr></table>'.$new_line;
							$message.='<table width=600px style="vertical-align:top;"><tr><td><h1>';
							$message.=$admin_email_subject;
							$message.='</h1></td></tr><tr><td>';
							$message.=$admin_email_message;
							$message.='<br><br>Connect to http://www.kleidos.tk to continue the adventure.</td></tr></table>'.$new_line;
							$message.='<br><table width=600px style="border-top: 1px solid #FF8000; border-bottom: 1px solid #FF8000; padding:2px; vertical-align:center;"><tr><td style="text-align:left; width=500px;"><a href="mailto:guardians@kleidos.tk">Contact the guardians</a>.</td><td style="text-align:right"><a href="http://www.kleidos.tk">kleidos.tk</a></td></tr></table></div></body>'.$new_line.$new_line;
							
							echo_debug("ADMIN EMAIL | To:".$to."<br>");
							echo_debug("ADMIN EMAIL | Bcc:".$bcc."<br>");
							echo_debug("ADMIN EMAIL | Subject:".$subject."<br>");
							echo_debug("ADMIN EMAIL | Header:".$header."<br>");
							echo_debug("ADMIN EMAIL | Message:".$message."<br>");					
							
							//send message if email system active
							if(!canemail())
							{
								echo_debug("ADMIN EMAIL | Admin mail not sent<br>");
							}
							else
							{	
								try
								{
									if(!mail($to , $subject , $message, $header))
									echo_debug("ADMIN EMAIL | <strong>Your email has been sent!</strong><br>");
								}	
								catch (PDOException $e)
								{
									echo_debug("ADMIN EMAIL | Admin mail not sent<br>");
									die('Error : '.$e->getMessage());
								}
							}
						}
					}
				}
			}
			

			echo ("<h2>Send email report</h2><strong>Email sent!</strong><br>");
			echo_debug("ADMIN EMAIL | TESTFLAG ".$_SESSION['TESTFLAG']);
			//if not in test mode display a warning and list of email addresses
			if($_SESSION['TESTFLAG']==0)
			{
				echo "Test mode is OFF. Your email has been sent to all players.<br>";
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
				echo "Test mode is ON, Your email has been sent to the guardians.<br>";
			}
			echo('<table style="width:600px;">');
			echo('<tr><td style="width:80px; vertical-align:top;">To:</td><td><b>'.$to.'</b></td><tr>');
			echo('<tr><td style="width:80px; vertical-align:top;">Bcc:</td><td class="bluetext">'.$bcc.'</td><tr>');
			echo('<tr><td style="width:80px; vertical-align:top;">Subject:</td><td><b>'.$subject.'</b></td><tr>');
			echo('<tr><td style="width:80px; vertical-align:top;">Header:</td><td class="bluetext">'.$header.'</td><tr>');
			echo('<tr><td style="width:80px; vertical-align:top;">Message:</td><td>'.$message.'</td><tr>');				
		?>
		<?php
			includeFooter();
		?>
	</body>
</html>