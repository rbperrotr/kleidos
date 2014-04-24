<?php
		
	//Confirm User password
	function user_confirmPassword($bdd, $login, $tempPass)
	{
		echo_debug("USER | Start user_confirmPassword");
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
			echo_debug("USER | fetch user_confirmPassword");			
		}
		
		if($tempPass == $user->getPassword())
		{
			return $user;
		}
		else
		{
			return false;
		}
		echo_debug("USER | End user_confirmPassword");
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
		echo_debug("User | get_FullName (nb rows=".$nb_rows.")");
		
		if($nb_rows > 0)
		{
			while ($data = $userquery->fetch())
			{
				$firstname = ($data['firstname']);
				$lastname = ($data['lastname']);
				$fullname = $firstname.$lastname;
				echo_debug("User | get_FullName = ".$firstname." ".$lastname."<br/>");
			}
		}
		else
		{
			$fullname = "";
		}
		return $fullname;
	}
	
	
	//Add User
	function user_addUser($bdd, $login, $firstname, $lastname, $password, $safeID)
	{
		$today = date("D M j G:i:s T Y");	//get today's date as a formatted string 
		$today = new DateTime($today);		//transform string $today as a date variable
		$today = $today->format("D M j G:i:s T Y");	//transform date variable into an object and set its dateformat
		/*
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		*/
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
				echo_debug("New user mail not sent");
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
					echo_debug("New user mail not sent");
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
					echo_debug("New user mail not sent");
					die('Erreur : '.$e->getMessage());
				}
			}
			
		}
		catch (PDOException $e)
		{
			die('Error : '.$e->getMessage());
		}
	}
?>