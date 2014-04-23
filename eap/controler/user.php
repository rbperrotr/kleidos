<?php
		
	//Confirm User password
	function user_confirmPassword($bdd, $login, $tempPass)
	{
		try
		{
			$reponse = $bdd->prepare('SELECT * FROM user WHERE login =:login');
			$reponse->execute(array(
				'login' => $login
			));
		}
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		
		while ($donnees = $reponse->fetch())
		{
			$user = new User($donnees['id'], $donnees['login'], $donnees['firstname'], $donnees['lastname'], $donnees['password'], $donnees['safeID']);	
		}
		
		if($tempPass == $user->getPassword())
		{
			return $user;
		}
		else
		{
			return false;
		}
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
		try
		{
			$reponse = $bdd->prepare('INSERT INTO user(login, firstname, lastname, password, safeID) VALUES(:login, :firstname, :lastname, :password, :safeID)');
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
				$message.="You have created an acount on ".$now." with email address ".$login.".".$newline.$newline;
				$message.="If you did not request for an account creation, then send an email to guardians@kleidos.tk.".$newline.$newline;
				$message.="Are you brave enough?".$newline;
				$message.="Connect to http://www.kleidos.tk to take part in the adventure.".$newline.$newline;
				$message.="________________________".$newline;
				$message.="The guardians of Kleidos".$newline;
				$message.="guardians@kleidos.tk".$newline;

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