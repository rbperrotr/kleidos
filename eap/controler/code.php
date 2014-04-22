<?php
	
	//Get all codes
	function code_getAllCodes($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM code');
		
		$codes = array();
		while ($donnees = $reponse->fetch())
		{
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['usedDate']);	
			$codes[] = $code;
		}
		
		return $codes;
	}
	
	//Get new codes
	function code_getNewCodes($bdd)
	{
		$reponse = $bdd->query("SELECT * FROM code where status = 'New'");
		
		$codes = array();
		while ($donnees = $reponse->fetch())
		{
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['usedDate']);	
			$codes[] = $code;
		}
		
		return $codes;
	}
	
	//Get a new code
	function code_getANewCode($bdd)
	{
		$reponse = $bdd->query("SELECT * FROM code where status = 'New'");
		
		while ($donnees = $reponse->fetch())
		{
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['usedDate']);	
		}
		
		return $code;
	}
	
	//Get codeID
	function code_getcodeID($bdd, $code_text)
	{
		$reponse = $bdd->query("SELECT id FROM code where text =".$code_text);
		while ($donnees = $reponse->fetch())
		{
			$codeID = $donnees['id'];
		}
		
		return $codeID;
	}
	
	//Assign a code
	function code_assignCode($bdd, $userID, $enigmaID, $codeID)
	{
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		try
		{
			$query = $bdd->query("UPDATE code SET userID=".$userID.", clueID='', status='Assigned', usedDate=NOW() WHERE id=".$codeID);
		}
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
	}
	
	//Close a code
	function code_useCode($bdd, $codeID, $userID)
	{
		//Getting enigmaID from the code
		try
		{
			$reponse = $bdd->query("SELECT enigmaID FROM code where id = ".$codeID);
		}
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		while ($donnees = $reponse->fetch())
		{
			$enigmaID = $donnees['enigmaID'];
		}
		
		
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
		
		//Getting total number of clues in enigma
		$nbClues = enigma_getCluesNb($bdd, $enigmaID);
		
		$clueRank = $clueRank + 1;
		
		try
		{
			$query = $bdd->query("INSERT INTO user_progress (userID, enigmaID, clueRank) VALUES (".$userID.", ".$enigmaID.", ".$clueRank.")");
			$query = $bdd->query("UPDATE rbpe.code SET status = 'Used', enigmaID=".$enigmaID." WHERE code.id =".$codeID);
		}
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
	}
	
	//get assigned code 
	function code_assignedcodes($bdd, $userID)
	{
		try
		{  
			$reponse = $bdd->prepare('SELECT text FROM code WHERE userID =:ui AND status="assigned"');
			$reponse->execute(array(
				'ui' => $userID
			));
		}	
		catch (Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		
		$codeText_list = array();
		while ($donnees = $reponse->fetch())
		{
			$codeText = $donnees['text']; 	
			$codeText_list[] = $codeText;
		}
		return $codeText_list;
	}
?>