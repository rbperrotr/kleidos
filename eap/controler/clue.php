<?php
	
	//Get all clues
	function clue_getAllClues($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM clue');
		
		$clues = array();
		while ($donnees = $reponse->fetch())
		{
			$clue = new Clue($donnees['id'], $donnees['text'], $donnees['codeID'], $donnees['order']);	
			$clues[] = $clue;
		}
		
		return $clues;		
	}
	
	//Get clue from code
	function clue_getCluesfromEnigma($bdd, $enigmaID)
	{
		$reponse = $bdd->query('SELECT * FROM clue WHERE enigmaID='.$enigmaID);
		
		$clues = array();
		while ($donnees = $reponse->fetch())
		{
			$clue = new Clue($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['order']);
			$clues[] = $clue;
		}
		
		return $clues;
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
			if($clue->getOrder() <= $clueRank)
			{
				$u_clues[] = $clue;
			}
		}
		
		return $u_clues;
	}
?>