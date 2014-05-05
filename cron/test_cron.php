<?php
	//Test cron script
	// onjective send email at 8:00 am
	// to all registered users
	// Kleidos status
	// for all enigma
	// if answered correctly, nothing
	// Else if answerd incorreclty some new hints may have been published, connect to Kleidos
	// il not answer, new enigma
	
	// check param Subscribe_to_email_notif
	$_SESSION['DEBUG']=0;
	require('../controler/bdd.php');
	require('../controler/user.php');
	require('../controler/global.php');
	require('../controler/admin.php');
	require('../controler/enigma.php');
	require('../controler/answer.php');
	require('../model/user.php');
	require('../model/enigma.php');
	
	$users = admin_getallusers($bdd);
	$enigmas = enigma_getAllEnigmas($bdd); //(Need to filter on publiDate
	
	//prepare email header
	$new_line = "\r\n";
	$boundary = "-----=".md5(rand());
	$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
	$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
	$header.= "MIME-Version: 1.0".$new_line;
	$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;
	$to="Guardians@kleidos.tk";
	
	
	foreach ($users as $user)
	{
		echo "<tr>".$user->getid()." ".$user->getlogin()." <br>";
		echo("<table border=1>");
		foreach ($enigmas as $enigma)
		{
			echo "<td>".$enigma->getid()." ".$enigma->gettitle()." <br>";
			
			if (answer_already_answered($bdd, $enigma->getid(), $user->getid())) //already answered.
			{
				echo("Was ".$enigma->getTitle()." too simple for you? In any case well done!<br>");
			}
			elseif (answer_already_answered_without_success($bdd, $enigma->getid(), $user->getid())) // wrongly answered
			{
				echo("Are you sure you read ".$enigma->getTitle()." correctly? Maybe you missed a hint, have you check? Else what about asking help to a colleague or to the Kleidos community?<br>");
			}
			elseif (enigma_isPublished($bdd, $enigma->getid())) //not answered
			{
				echo("Were you off line? It is never too late to answer. For the future don't leave all the codes to others! Connect to <a href='http://kleidos.tk'>Kleidos</a> to answer the latest enigmas.");
			}
			else{
				echo ("ispublished = ".enigma_isPublished($bdd, $enigma->getid())."<br>");
				echo("Euh c'est quel cas ça ?<br>");
			}
			echo("</td>");
		}
		
		echo("</tr>");
		echo "</table>";
		/*		
		$statusfrequency="Daily"; //or Weekly Or None
		$fullname = user_getFullName($bdd, $user);
		$subject="KLEIDOS status for ".$fullname;
		$now=date('Y-m-d H:i:s');
		$message="Hello,".$new_line.$new_line;
		$message.="This is your ".$statusfrequency." status for Kleidos. Thanks for playing with Kleidos.";
		*/
		/*
		if(!canemail())
		{
			echo_debug("New answer email not sent from localhost"); //catch hostname local to avoid error on mail
		}
		else
		{
			try
			{
				if(!mail($to , $subject , $message, $header));
			}	
			catch (PDOException $e)
			{
				echo_debug("New answer email not sent");
				die('Erreur : '.$e->getMessage());
			}
		}
		*/
	
	}
?>
