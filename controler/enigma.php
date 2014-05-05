<?php
	
	//Get all enigmas
	//OLD
	/*function enigma_getAllEnigmas($bdd)
	{
		$response = $bdd->query('SELECT * FROM enigma');
		return $response;
	}
	*/
	
	
	function enigma_getAllEnigmas($bdd)
	{
		$query = $bdd->prepare('SELECT * FROM enigma');
		$query->execute(array(
		));
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("enigma_getAllEnigmas | nbrows =".$nb_rows."<br/>");
		$enigmas = array();
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$enigma = new Enigma($data['id'], $data['title'], $data['text'], $data['nbClues'], $data['publiDate'], $data['ref'], $data['picture'], $data['expected_answer'], $data['buy_clue']);
				$enigmas[] = $enigma;
			}
		}
		return $enigmas;
	}
	
	//get only viewable enigmas
	function enigma_getViewableEnigmas($bdd)
	{
		$enigmas = enigma_getAllEnigmas($bdd);
		
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		
		$viewableEnigmas = array();
		foreach($enigmas as $enigma)
		{
			$publi = (string)$enigma->getPubliDate();
			$publi = new DateTime($publi);
			$publi = $publi->format('Ymd');
			if($today >= $publi)
			{
				$viewableEnigmas[] = $enigma;
			}
		}
		/*OLD
		while ($data = $response->fetch())
		{
			$enigma = new Enigma($data['id'], $data['title'], $data['text'], $data['nbClues'], $data['publiDate'], $data['ref'], $data['picture'], $data['expected_answer'], $data['buy_clue']);
			$publi = (string)$enigma->getPubliDate();
			$publi = new DateTime($publi);
			$publi = $publi->format('Ymd');
			
			if($today >= $publi)
			{
				$enigmas[] = $enigma;
			}
		}
		*/
		
		return $viewableEnigmas;
	}
	
	//returns TRUE if enigma already published
	function enigma_isPublished($bdd, $EnigmaID)
	{
		$query = $bdd->prepare('SELECT publiDate FROM enigma WHERE ID = :ei');
		$query->execute(array(
				'ei' => $EnigmaID
		));
		
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		$isPublished = FALSE;
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("enigma_isPublished | nbrows =".$nb_rows."<br/>");
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$publiDate = ($data['publiDate']);
				$publiDate = new DateTime($publiDate);
				$publiDate = $publiDate->format('Ymd');
				if($today >= $publiDate)
					$isPublished = TRUE;
				else
					$isPublished = FALSE;
			}
		}
		return $isPublished;
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