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
			$passage_ligne = "\r\n";
			$header = "From: \"The guardians\"<guardians@kleidos.tk>".$passage_ligne;			$header.= "Reply-to: \"WeaponsB\" <weaponsb@mail.fr>".$passage_ligne;				$header.= "MIME-Version: 1.0".$passage_ligne;							$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

			$to="Guardians@kleidos.tk";
			$subject="NEW USER: ".$firstname." ".$lastname;
			$now=date('Y-m-d H:i:s');
			$message="A new user has just signed up on ".$now;
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
		catch (PDOException $e)
		{
			die('Erreur : '.$e->getMessage());
		}
	}
?>