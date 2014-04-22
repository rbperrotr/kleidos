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
			//prepare email for notification to the guardians
			$new_line = "\r\n";
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
				die('Error : '.$e->getMessage());
			}
		}
		catch (PDOException $e)
		{
			die('Error : '.$e->getMessage());
		}
	}
?>