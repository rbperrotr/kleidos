<?php
	
	//Get all enigma
	function enigma_getAllEnigmas($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM enigma');
		return $reponse;
	}
	
	//get only viewable enigmas
	function enigma_getViewableEnigmas($bdd)
	{
		$reponse = enigma_getAllEnigmas($bdd);
		
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		
		$enigmas = array();
		
		while ($donnees = $reponse->fetch())
		{
			$enigma = new Enigma($donnees['id'], $donnees['title'], $donnees['text'], $donnees['nbClues'], $donnees['publiDate'], $donnees['ref'], $donnees['picture'], $donnees['expected_answer'], $donnees['buy_clue']);
			$publi = (string)$enigma->getPubliDate();
			$publi = new DateTime($publi);
			$publi = $publi->format('Ymd');
			
			if($today >= $publi)
			{
				$enigmas[] = $enigma;
			}
		}
		
		return $enigmas;
	}
	
	//get total number of clues for enigma
	function enigma_getCluesNb($bdd, $ref)
	{
		try
		{
			$reponse = $bdd->query('SELECT nbClues FROM enigma WHERE ref='.$ref);
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		
		while ($donnees = $reponse->fetch())
		{
			$nbClues = $donnees['nbClues'];
		}
		
		return 	$nbClues;
	}
	
	//get Buy_clue - TRUE if it is possible to buy clue
	function enigma_getBuyClue($bdd, $ref)
	{
		try
		{
			$reponse = $bdd->query('SELECT buy_clue FROM enigma WHERE ref='.$ref);
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		
		while ($donnees = $reponse->fetch())
		{
			$buy_clue = $donnees['buy_clue'];
		}
		
		return 	$buy_clue;
	}
	
	//get 1 enigma
	function enigma_getEnigma($bdd, $ref)
	{
		$reponse = $bdd->prepare('SELECT * FROM enigma WHERE ref = :ref');
		$reponse->execute(array(
				'ref' => $ref
		));
		while ($donnees = $reponse->fetch())
		{
			$enigma = new Enigma($donnees['id'], $donnees['title'], $donnees['text'], $donnees['nbClues'], $donnees['publiDate'], $donnees['ref'], $donnees['picture'], $donnees['expected_answer'], $donnees['buy_clue']);
		}
		return $enigma;
	}
?>