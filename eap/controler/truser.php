<?php
	
	//Get all trusers
	function truser_getAllTRUsers($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM truser');
		
		$truser = array();
		while ($donnees = $reponse->fetch())
		{
			$truser = new TRUser($donnees['id'], $donnees['email'], $donnees['safeID']);	
			$trusers[] = $truser;
		}
		
		return $trusers;		
	}
	
	//Get truserid from safeid
	function truser_getTRUserfromsafeid($bdd, $safeID)
	{
		try
		{
			$reponse = $bdd->prepare("SELECT id FROM truser where safeID = :safeID");
			$reponse->execute(array(
				'safeID' => $safeID
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		try
		{
			$nb_rows = $reponse->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		if($nb_rows > 0)
		{
			while ($donnees = $reponse->fetch())
			{
				$truserid = $donnees['id'];
			}
		}
		else
		{
			$truserid = -1;
		}
		return $truserid;
	}
	
	//Get truserid from email
	function truser_getTRUserfromemail($bdd, $email)
	{
	try
		{
			$reponse = $bdd->prepare("SELECT id FROM truser where email = :email");
			$reponse->execute(array(
				'email' => $email
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		try
		{
			$nb_rows = $reponse->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		if($nb_rows > 0)
		{
			while ($donnees = $reponse->fetch())
			{
				$truserid = $donnees['id'];
			}
		}
		else
		{
			$truserid = -1;
		}
		return $truserid;
	}

	function truser_check_email_safeID_compliance($bdd, $email, $safeid)
	{
		$truis=truser_getTRUserfromsafeid($bdd, $safeid);
		$truie=truser_getTRUserfromemail($bdd, $email);
		if($truis!=-1 && $truie!=-1)
		{
			if($truis==$truie)
			{
				echo_debug("TRUSER | email and safeID compliant");
				return TRUE;
			}
			else
				return FALSE;
		}
		else
		{
			echo_debug("TRUSER | email and safeID not found");
			return FALSE;
		}
	}
?>