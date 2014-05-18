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
				$action = "";
				if(htmlspecialchars($_POST['action']) == "send_email_allplayers")
					$action = "all";
				elseif(htmlspecialchars($_POST['action']) == "send_email_test")
					$actions = "test";
				elseif(htmlspecialchars($_POST['action']) == "send_email_someplayers")
					$action = "some";
				else
					$action = "";
					
				//check fields are not empty
				$checks_isset =0;
				/*
				if($action == "all")
				elseif($action == "some")
				elseif($action == "test")
				else
				*/
				
				//Check fields are set
				if(isset($_POST['admin_email_subject']) && isset($_POST['admin_email_message']))
				{
					$admin_email_subject = htmlspecialchars($_POST['admin_email_subject']);
					$admin_email_message = nl2br(($_POST['admin_email_message']));	
					if($action == "some")
					{
						if(isset($_POST['admin_some_players_email']))
						{
							$someplayersemails = htmlspecialchars($_POST['admin_some_players_email']);
							$checks_isset =1;
						}
						else
							$someplayersemails = "None";
					}
					else
						$checks_isset =1;
				}		
				
				//check fields are not empty
				if($checks_isset == 1)
				{
					echo_debug ('ADMIN EMAIL RESULT | checks on fields OK (no unset fields)<br><br>');
					echo_debug ('ADMIN EMAIL RESULT | subject: '.$admin_email_subject.'.<br>');
					echo_debug ('ADMIN EMAIL RESULT | ------- Message begin ------- <br>');
					echo_debug ('ADMIN EMAIL RESULT | message: '.htmlentities($admin_email_message).'<br>');
					echo_debug ('ADMIN EMAIL RESULT | -------- Message end -------- <br>');
					
					//check subject and message not empty else return error
					if($admin_email_subject == "")
					{	
						echo ("<strong>Message not send. Enter a subject</strong><br>");
					}
					elseif($admin_email_message == "")
					{
						echo ("<strong>Message not send. Enter a message</strong><br>");
					}
					elseif($action == "some" && $someplayersemails == "")
					{
						echo ("<strong>Message not send. Enter a list of email addresses.<br></strong><br>");
					}
					else
					{
						echo_debug ('ADMIN EMAIL RESULT | checks on fields OK (no empty fields)<br>');
						//prepare email
						$new_line = "\r\n";
						$boundary = "-----=".md5(rand());
						$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
						$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
						
						//prepare distribution list
						//if test only guardians
						//if someplayers then add there email address as bcc
						//if allplayers then add there email address as bcc
						$AllEmails = admin_getallemails($bdd);
						$to = "guardians@kleidos.tk";
						$admin_email_subject_prefix = "";
						if($action == "all")
						{
							echo_debug ("ADMIN EMAIL RESULT | Send to all players - build bcc list.<br>");
							foreach ($AllEmails as $anemail)
							{	
								$bcc.= $anemail.", ";
							}
							$header.= "Bcc: ".$bcc.$new_line;
							$admin_email_subject_prefix = "[Kleidos: Message to all players] ";
						}
						elseif($action == "some")
						{							
							echo_debug ("ADMIN EMAIL RESULT | Send to some players - build bcc list.<br>");
							$bcc.= $someplayersemails;
							$header.= "Bcc: ".$bcc.$new_line;
							$admin_email_subject_prefix = "[Kleidos: Message to players] ";	
						}
						
						//Finalize header
						$header.= "MIME-Version: 1.0".$new_line;
						$header.= "Content-Type: text/html; charset=ISO-8859-1".$new_line." boundary=\"$boundary\"".$new_line;			
						
						$subject=$admin_email_subject_prefix.$admin_email_subject;
						$now=date('Y-m-d H:i:s');
						
						//prepare HTML message with a 600px table, a header row, a message row including a signature and a footer row with link to kleidos
						$message='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" ><head><meta http-equiv="Content-Type" content="text/html; charset="utf-8"; /><link rel="stylesheet" href="http://www.kleidos.tk/style.css" /></head>'.$new_line;
						$message.='<table style="width:600px; "><tr><td>';
						$message.='<body><table style="width: 100%; border-top: 2px solid #FF8000; border-bottom: 2px solid #FF8000; padding:7px;"><tr>	<td style="text-align:left; width=500px;"><strong>Message from the Guardians of Kleidos</strong></td><td><img src="http://www.kleidos.tk/resources/small logo.png" height="55"/></td></tr></table>'.$new_line;
						
						$message.='<table style="width: 100%; vertical-align:top;"><tr><td><h1>';
						$message.=$admin_email_subject;
						$message.='</h1></td></tr><tr><td>';
						$message.=$admin_email_message;
						$message.='<br><br>Connect to http://www.kleidos.tk to continue the adventure.</td></tr></table>'.$new_line;
						$message.='<br><table style="width: 100%; border-top: 1px solid #FF8000; border-bottom: 1px solid #FF8000; padding:2px; vertical-align:center;"><tr><td style="text-align:left; width=500px;"><a href="mailto:guardians@kleidos.tk">Contact the guardians</a>.</td><td style="text-align:right"><a href="http://www.kleidos.tk">kleidos.tk</a></td></tr></table>';
						$message.='</td></tr></table>';
						$message.='</body>'.$new_line.$new_line;
						
						echo_debug("ADMIN EMAIL RESULT | To:".$to."<br>");
						echo_debug("ADMIN EMAIL RESULT | Bcc:".$bcc."<br>");
						echo_debug("ADMIN EMAIL RESULT | Subject:".$subject."<br>");
						echo_debug("ADMIN EMAIL RESULT | Header:".$header."<br>");
						echo_debug("ADMIN EMAIL RESULT | Message:".$message."<br>");					
						
						//send message if email system active
						$sent = 0;
						if(!canemail())
						{
							echo_debug("ADMIN EMAIL RESULT | Email deactivated - Admin mail not sent<br>");
						}
						else
						{	
							echo_debug("ADMIN EMAIL RESULT | TESTFLAG ".$_SESSION['TESTFLAG']);
							//try sending and display title of send report, a report is displayed in anycase
							
							//REPRENDRE ICI, la réponse du sent n'est pas correcte, j'affiche Email sent dans tous les cas
							if($action == "test")
							{
								echo('Test mode is ON.<br>');
								echo_debug('ADMIN EMAIL RESULT | TESTFLAG is OFF --- TRYING TO SEND EMAIL TO ALL PLAYERS ---<br>');
								try
								{
									$sent = mail($to , $subject , $message, $header);
								}	
								catch (PDOException $e)
								{
									echo_debug("ADMIN EMAIL | Admin mail not sent<br>");
									die('Error : '.$e->getMessage());
								}
								echo_debug('ADMIN EMAIL RESULT | sent='.$sent.'.<br');								
							}
							elseif($action == "all")
							{
								echo ('Test mode is OFF.<br>');
								echo_debug('ADMIN EMAIL RESULT | TESTFLAG is OFF --- TRYING TO SEND EMAIL TO ALL PLAYERS ---<br>');
								try
								{
									$sent = mail($to , $subject , $message, $header);
								}	
								catch (PDOException $e)
								{
									echo_debug("ADMIN EMAIL | Admin mail not sent<br>");
									die('Error : '.$e->getMessage());
								}
								echo_debug('ADMIN EMAIL RESULT | sent='.$sent.'.<br');								
							}
							elseif($action == "some")
							{
								echo('Test mode is OFF.<br>');
								echo_debug('ADMIN EMAIL RESULT | TESTFLAG is OFF --- TRYING TO SEND EMAIL TO SOME PLAYERS ---<br>');
								try
								{
									$sent = mail($to , $subject , $message, $header);
								}	
								catch (PDOException $e)
								{
									echo_debug("ADMIN EMAIL | Admin mail not sent<br>");
									die('Error : '.$e->getMessage());
								}
								echo_debug('ADMIN EMAIL RESULT | sent='.$sent.'.<br');
							}
							
							if($sent)
							{
								echo_debug("ADMIN EMAIL | <strong>Your email has been sent!</strong><br>");
								echo ("<h2>Sent email report</h2><strong>Email sent!</strong><br>");
							}
							else
							{
								echo_debug("ADMIN EMAIL | <strong>Your email has NOT been sent!</strong><br>");
								echo ("<h2>Not sent email report</h2><strong>Email sent!</strong><br>");
							}		
						}
						
						//Display report
						if($action == "all")
						{
							//$emails = admin_getallemails($bdd);
							echo('<br><a href="javascript:unhide(\'UsersAllEmails\');">Display/hide bcc distribution list</a><br>');
							echo('<div id="UsersAllEmails" class="admHidden">');
							echo('<table style="width:800px" class="bluetext"><tr><td>');
							foreach ($AllEmails as $anemail)
							{
								echo ("".$anemail."; ");
							}
							echo('</td></tr></table></div>');					
						}
						elseif($action == "some")
						{
							echo('<br><a href="javascript:unhide(\'UsersSomeEmails\');">Display/hide bcc distribution list</a><br>');
							echo('<div id="UsersSomeEmails" class="admHidden">');
							echo('<table style="width:800px" class="bluetext"><tr><td>');
							echo($someplayersemails);
							echo('</td></tr></table></div>');
						
						}

						echo('<table style="width:600px;">');
						echo('<tr><td style="width:80px; vertical-align:top;">To:</td><td><b>'.$to.'</b></td><tr>');
						echo('<tr><td style="width:80px; vertical-align:top;">Bcc:</td><td class="bluetext">'.$bcc.'</td><tr>');
						echo('<tr><td style="width:80px; vertical-align:top;">Subject:</td><td><b>'.$subject.'</b></td><tr>');
						echo('<tr><td style="width:80px; vertical-align:top;">Header:</td><td class="bluetext">'.$header.'</td><tr>');
						echo('<tr><td style="width:80px; vertical-align:top;">Message:</td><td>'.$message.'</td><tr>');				
					}
				}
				else
					echo_debug('ADMIN EMAIL RESULT | no email sent, one field is empty<br>>');
			}
		?>

	</body>
</html>