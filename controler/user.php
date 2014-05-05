<?php
		
	//Confirm User password
	function user_confirmPassword($bdd, $login, $tempPass)
	{
		echo_debug("USER | Start user_confirmPassword<br>");
		try
		{
			$reponse = $bdd->prepare('SELECT * FROM user WHERE login =:login');
			$reponse->execute(array(
				'login' => $login
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		while ($data = $reponse->fetch())
		{
			$user = new User($data['id'], $data['login'], $data['firstname'], $data['lastname'], $data['password'], $data['safeID'], $data['registrationdate']);	
			echo_debug("USER | fetch user_confirmPassword<br>");			
		}
		
		if($tempPass == $user->getPassword())
		{
			return $user;
		}
		else
		{
			return false;
		}
		echo_debug("USER | End user_confirmPassword<br>");
	}
	
	//Get Full Name = firstname lastname
	function user_getFullName($bdd, $userID)
	{
		$userquery = $bdd->prepare('SELECT firstname, lastname FROM user WHERE id= :ui2');
		$userquery->execute(array(
				'ui2' => $userID
		));
		try
		{
			$nb_rows = $userquery->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("User | get_FullName (nb rows=".$nb_rows.")<br>");
		
		if($nb_rows > 0)
		{
			while ($data = $userquery->fetch())
			{
				$firstname = ($data['firstname']);
				$lastname = ($data['lastname']);
				$fullname = $firstname." ".$lastname;
				echo_debug("User | get_FullName = ".$firstname." ".$lastname."<br/>");
			}
		}
		else
		{
			$fullname = "";
		}
		return $fullname;
	}
	
	//Get Login
	function user_getLogin($bdd, $userID)
	{
		$userquery = $bdd->prepare('SELECT login FROM user WHERE id= :ui3');
		$userquery->execute(array(
				'ui3' => $userID
		));
		try
		{
			$nb_rows = $userquery->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("User | get_Login (nb rows=".$nb_rows.")<br>");
		
		if($nb_rows > 0)
		{
			while ($data = $userquery->fetch())
			{
				$login = ($data['login']);
				echo_debug("User | get_Login = ".$login."<br/>");
			}
		}
		else
		{
			$login = "";
		}
		return $login;
	}
	
	//Add User
	function user_addUser($bdd, $login, $firstname, $lastname, $password, $safeID)
	{
		$today = date("D M j G:i:s T Y");	//get today's date as a formatted string 
		$today = new DateTime($today);		//transform string $today as a date variable
		$today = $today->format("D M j G:i:s T Y");	//transform date variable into an object and set its dateformat
		
		echo_debug("today in user_addUser = ".$today."<br>");
				
		try
		{
			$reponse = $bdd->prepare('INSERT INTO user(login, firstname, lastname, password, safeID, registrationdate) VALUES(:login, :firstname, :lastname, :password, :safeID, NOW())');
			$reponse->execute(array(
				'login' => $login,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'password' => $password,
				'safeID' => $safeID
			));		
			
			//email notifications 
			if(!canemail())
			{
				echo_debug("New user mail not sent<br>");
			}
			else
			{
				//prepare email for notification to the guardians
				$new_line = "\r\n";
				$boundary = "-----=".md5(rand());
				$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
				$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
				$header.= "MIME-Version: 1.0".$new_line;
				$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

				$to="Guardians@kleidos.tk";
				$subject="NEW USER: ".$firstname." ".$lastname;
				$now=date('Y-m-d H:i:s');
				$message="A new user has just signed up on ".$now.$new_line;
				$message.="with email address ".$login;

				try
				{
					if(!mail($to , $subject , $message, $header));
				}	
				catch (PDOException $e)
				{
					echo_debug("New user mail not sent<br>");
					die('Erreur : '.$e->getMessage());
				}
				
				//prepare email for notification to the new user
				$new_line = "\r\n";
				$boundary = "-----=".md5(rand());
				$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
				$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
				$header.= "MIME-Version: 1.0".$new_line;
				$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

				$to=$login;
				$subject="Welcome in Kleidos world!";
				$now=date('Y-m-d H:i:s');
				$message="Hello ".$firstname." ".$lastname.".".$new_line;
				$message.="You have created an acount on ".$now." with email address ".$login.".".$new_line.$new_line;
				$message.="If you did not request for an account creation, then send an email to guardians@kleidos.tk.".$new_line.$new_line;
				$message.="Are you brave enough?".$new_line;
				$message.="Connect to http://www.kleidos.tk to take part in the adventure.".$new_line.$new_line;
				$message.="________________________".$new_line;
				$message.="The guardians of Kleidos".$new_line;
				$message.="guardians@kleidos.tk".$new_line;

				try
				{
					if(!mail($to , $subject , $message, $header));
				}	
				catch (PDOException $e)
				{
					echo_debug("New user mail not sent<br>");
					die('Erreur : '.$e->getMessage());
				}
			}
			
		}
		catch (PDOException $e)
		{
			die('Error : '.$e->getMessage());
		}
	}

	function user_changePassword($bdd, $userID, $MD5password)
	{
		$today = date("D M j G:i:s T Y");	//get today's date as a formatted string 
		$today = new DateTime($today);		//transform string $today as a date variable
		$today = $today->format("D M j G:i:s T Y");	//transform date variable into an object and set its dateformat
		$fullname = user_getFullName($bdd, $userID);
		$login = user_getLogin($bdd,$userID);
		
		echo_debug("today in user_changePassword = ".$today."<br>");
				
		try
		{	
			echo_debug("USER | user_changePassword for userID=".$userID." fullname=".$fullname."<br>");
			try
			{
				$query = $bdd->query("UPDATE user SET password=\"".$MD5password."\" WHERE id=".$userID);
				echo ("Your new password has been saved.<br>");
			}
			catch (Exception $e)
			{
				echo ("<strong>Your new password has not been saved.<strong><br>");
				die('Error : '.$e->getMessage());
			}
			
			//email notifications 
			if(!canemail())
			{
				echo_debug("User pwd change user mail not sent<br>");
			}
			else
			{
				//prepare email for notification to the guardians
				$new_line = "\r\n";
				$boundary = "-----=".md5(rand());
				$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
				$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
				$header.= "MIME-Version: 1.0".$new_line;
				$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

				$to="Guardians@kleidos.tk";
				$subject="USER PASSWORD CHANGED: ".$fullname;
				$now=date('Y-m-d H:i:s');
				$message="The user ".$fullname." has changed his/her password on ".$now.$new_line;

				try
				{
					if(!mail($to , $subject , $message, $header));
				}	
				catch (PDOException $e)
				{
					echo_debug("User (".$fullname.") password changed mail not sent<br>");
					die('Error : '.$e->getMessage());
				}
				
				//prepare email for notification to the new user
				$new_line = "\r\n";
				$boundary = "-----=".md5(rand());
				$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
				$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
				$header.= "MIME-Version: 1.0".$new_line;
				$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

				$to=$login;
				$subject="Kleidos: password changed notification";
				$now=date('Y-m-d H:i:s');
				$message="Hello ".$fullname.".".$new_line;
				$message.="You have changed your password on ".$now.$new_line.$new_line;
				$message.="If you did not submit a password change, then send an email to guardians@kleidos.tk.".$new_line.$new_line;
				$message.="Thanks playing with Kleidos!".$new_line;
				$message.="Connect to http://www.kleidos.tk to continue the adventure.".$new_line.$new_line;
				$message.="________________________".$new_line;
				$message.="The guardians of Kleidos".$new_line;
				$message.="guardians@kleidos.tk".$new_line;

				try
				{
					if(!mail($to , $subject , $message, $header));
				}	
				catch (PDOException $e)
				{
					echo_debug("New user mail not sent<br>");
					die('Erreur : '.$e->getMessage());
				}
			}
			
		}
		catch (PDOException $e)
		{
			die('Error : '.$e->getMessage());
		}	
		/*
		*/
	}
	
	//get email Notif frequency
	function user_getEmailFrequency($bdd, $userID)
	{
		echo_debug("User | Start get_emailNotif <br>");
		$userquery = $bdd->prepare('SELECT emailNotif as emailNotif FROM user WHERE id= :ui2');
		$userquery->execute(array(
				'ui2' => $userID
		));
		try
		{
			$nb_rows = $userquery->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("User | get_emailNotif (nb rows=".$nb_rows.")<br>");
		
		if($nb_rows > 0)
		{
			while ($data = $userquery->fetch())
			{
				$emailNotif = ($data['emailNotif']);
				echo_debug("User | get_EmailNotif = ".$emailNotif."<br/>");
			}
		}
		else
		{
			$emailNotif = "None";
		}
		return $emailNotif;
	}
	
	//get email Other Players (Set to Yes if user accepts to receive emails from others players)
	function user_getEmailOtherPlayers($bdd, $userID)
	{
		echo_debug("User | Start get_emailOtherPlayers <br>");
		$userquery = $bdd->prepare('SELECT emailOtherPlayers as emailOtherPlayers FROM user WHERE id= :ui2');
		$userquery->execute(array(
				'ui2' => $userID
		));
		try
		{
			$nb_rows = $userquery->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("User | get_emailOtherPlayers (nb rows=".$nb_rows.")<br>");
		
		if($nb_rows > 0)
		{
			while ($data = $userquery->fetch())
			{
				$emailOtherPlayers = ($data['emailOtherPlayers']);
				echo_debug("User | get_emailOtherPlayers = ".$emailOtherPlayers."<br/>");
			}
		}
		else
		{
			$emailOtherPlayers = "No";
		}
		return $emailOtherPlayers;
	}
?>
