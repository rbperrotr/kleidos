<?php
	
	//Get all clues
	function clue_getAllClues($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM clue');
		
		$clues = array();
		while ($donnees = $reponse->fetch())
		{
			$clue = new Clue($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['sortID'], $donnees['publishedDate']);	
			$clues[] = $clue;
		}
		
		return $clues;		
	}
	
	//Get clue from for a given EnigmaID
	function clue_getCluesfromEnigma($bdd, $enigmaID)
	{
		echo_debug("CLUE | Start function clue_getCluesfromEnigma enigmaID=".$enigmaID."<br>");
		$reponse = $bdd->query('	'.$enigmaID);
		
		$clues = array();
		while ($donnees = $reponse->fetch())
		{
			$clue = new Clue($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['sortID'], $donnees['publishedDate']);
			$clues[] = $clue;
		}
		
		return $clues;
		echo_debug("CLUE | End function clue_getCluesfromEnigma<br>");
	}
	
	
	function clue_getCluesofUser($bdd, $enigmaID, $userID, $clues)
	{
		//Getting last rank of clues for the enigma the user have
		try
		{
			$reponse = $bdd->query("SELECT * FROM user_progress where userID = '".$userID."' AND enigmaID = '".$enigmaID."'");
			
		}
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
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
				$clueRank = $donnees['clueRank'];
			}
		}
		else
		{
			$clueRank = 0;
		}
		
		$u_clues = array();
		foreach($clues as $clue)
		{
			if($clue->getsortID() <= $clueRank)
			{
				$u_clues[] = $clue;
			}
		}
		
		return $u_clues;
	}
	
	/**********************************************************************************************
	 * Return the list of clues ready for publication based on date for a given EnigmaID
	 **********************************************************************************************/
	function clue_getCluesToPublish ($bdd, $enigmaID)
	{
		try
		{  
			$response = $bdd->prepare('SELECT * FROM clue WHERE enigmaID =:ei');
			$response->execute(array(
				'ei' => $enigmaID
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		
		$clues = array();
		while($data = $response->fetch())
		{
			$clue = new Clue($data['id'], $data['text'], $data['enigmaID'], $data['sortID'], $data['publishedDate']);
			$publicationDate = (string)$clue->getPublishedDate();
			$publicationDate = new DateTime($publicationDate);
			$publicationDate = $publicationDate->format('Ymd');
			
			if($today >= $publicationDate)
			{
				$clues[] = $clue;
			}
		}
		return $clues;		
	}
	
	/**********************************************************************************************
	 * Return the date of clue number N for a given EnigmaID
	 **********************************************************************************************/
	function clue_getClueOnePublicationDate ($bdd, $enigmaID, $sortID)
	{
		try
		{  
			$response = $bdd->prepare('SELECT * FROM clue WHERE enigmaID =:ei and sortID=:si');
			$response->execute(array(
				'ei' => $enigmaID,
				'si' => $sortID
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}

		while($data = $response->fetch())
		{
			$clue = new Clue($data['id'], $data['text'], $data['enigmaID'], $data['sortID'], $data['publishedDate']);
			$publicationDate = (string)$clue->getPublishedDate();
			$publicationDate = new DateTime($publicationDate);
			$publicationDate = $publicationDate->format('Ymd');
			
		}
		return $publicationDate;	
	}
	
?>