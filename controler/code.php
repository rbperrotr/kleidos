<?php
	
	//Get all codes
	function code_getAllCodes($bdd)
	{
		$reponse = $bdd->query('SELECT * FROM code');
		
		$codes = array();
		while ($donnees = $reponse->fetch())
		{
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['assignedDate'], $donnees['usedDate']);	
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
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['assignedDate'], $donnees['usedDate']);	
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
			$code = new Code($donnees['id'], $donnees['text'], $donnees['enigmaID'], $donnees['userID'], $donnees['clueID'], $donnees['status'], $donnees['assignedDate'], $donnees['usedDate']);	
		}
		
		return $code;
	}
	
	//Get codeID from text entered by user
	function code_getcodeID($bdd, $code_text)
	{
		echo_debug("CODE getcodeID | Start<br>");
		try
		{  
			$response = $bdd->prepare('SELECT id FROM code where text =:tt');
			$response->execute(array(
				'tt' => $code_text
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		try
		{
			$nb_rows = $response->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		$codeID=0;
		if($nb_rows > 0)
		{	
			if($nb_rows > 0)
			{
				while ($data = $response->fetch())
				{
					$codeID = $data['id'];
				}
			}
			echo_debug("CODE getcodeID | End with CodeId=".$codeID."<br>");
			return $codeID;
		}
		else
		{
			echo_debug("CODE getcodeID | End - No Code found<br>");
			return 0;
		}
	}
	
	//Assign a code
	function code_assignCode($bdd, $userID, $enigmaID, $codeID)
	{
		$today = date('Y-m-d');	
		$today = new DateTime($today);
		$today = $today->format('Ymd');
		try
		{
			$query = $bdd->query("UPDATE code SET userID=".$userID.", clueID='', status='Assigned', assignedDate=NOW() WHERE id=".$codeID);
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
	}
	
	//Buy a hint with a code
	function code_buyAHint($bdd, $codeID, $enigmaID, $userID)
	{
		//Check on CodeID are ALREADY DONE (Code ID belongs to the user, CodeID is available (Status=Assigned)
		//return 1 if hint bought else return -1 if error or no hint to buy
		$returnCode = -1;
		
		echo_debug("CODE buyAHint | Start<br>");
		//identify the first clue not yet published
		//update the codeID with enigmaID, clueID, Status, UsedDate
	
		echo_debug('CODE buyAHint | Get the first clue not yet published not bought by the user='.$userID.' for enigmaID='.$enigmaID.'<br>');
		//Get the first clue not yet published not bought by the user for the given enigmaID
		/*
		SELECT clue.id
		FROM clue 
		WHERE
			clue.enigmaID=12 AND
			clue.publishedDate>now() AND
			clue.id NOT IN
				(
				SELECT code.clueID
				FROM code
				WHERE
					code.userID=3 AND
					code.status="Used"
				)
		ORDER BY clue.sortID
		LIMIT 0, 1
		*/
		try
		{  
			$response = $bdd->prepare('SELECT clue.id AS clueID FROM clue WHERE clue.enigmaID=:ei AND clue.publishedDate>NOW() AND clue.id NOT IN (SELECT code.clueID FROM code WHERE code.userID=:ui AND code.status="Used") ORDER BY clue.sortID LIMIT 0, 1');
			$response->execute(array(
				'ui' => $userID, 
				'ei' => $enigmaID
			));
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		$clueID=0;
		try
		{
			$nb_rows = $response->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug('CODE buyAHint | search clue nbRows='.$nb_rows.'<br>');
		if($nb_rows > 0)
		{
			while ($data = $response->fetch())
			{
				$clueID = $data['clueID'];
			}
		}
		
		
		
		//update the codeID with enigmaID, clueID, Status, UsedDate
		if($clueID!=0)
		{
			echo_debug("CODE buyAHint | Update Code with CodeID=".$codeID."(enigmaID=".$enigmaID.", Status=Used, usedDate=Now(), clueID=".$clueID.")<br>");
			try
			{
				$query = $bdd->query('UPDATE code SET status = "Used", enigmaID='.$enigmaID.', clueID='.$clueID.', usedDate=NOW() WHERE code.id ='.$codeID);
			}
			catch (Exception $e)
			{
				die('Error : '.$e->getMessage());
			}
			$returnCode = 1;
		}
		else{
			echo_debug("CODE buyAHint | No Hint to buy");
		}
		echo_debug("CODE buyAHint | End");
		return $returnCode;
	}
	
	//Use a code to buy a clue
	function OLDcode_useCode($bdd, $codeID, $userID)
	{
		//Getting enigmaID from the code
		try
		{
			$reponse = $bdd->query("SELECT enigmaID FROM code where id = ".$codeID);
		}
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
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
	function code_assignedCodesByUser($bdd, $userID)
	{
		echo_debug('CODE code_assignedCodesByUser | userID='.$userID.'<br>');
		try
		{  
			$reponse = $bdd->prepare('SELECT text FROM code WHERE userID =:ui AND status="assigned"');
			$reponse->execute(array(
				'ui' => $userID
			));
		}	
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		$codeText_list = array();
		while ($donnees = $reponse->fetch())
		{
			$codeText = $donnees['text']; 	
			$codeText_list[] = $codeText;
		}
		return $codeText_list;
	}

	//confirm a code belongs to a user
	function code_checkCodeUser($bdd, $CodeID, $userID)
	{
		echo_debug('CODE code_checkCodeUser | codeID='.$CodeID.' userID='.$userID.'<br>');
		try
		{  
			$response = $bdd->prepare('SELECT userID FROM code WHERE id=:ci');
			$response->execute(array(
				'ci' => $CodeID
			));
		}	
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		try
		{
			$nb_rows = $response->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		$codeUser=0;
		$check=FALSE;
		if($nb_rows > 0)
		{
			while ($data = $response->fetch())
			{
				$codeUser = $data['userID']; 	
			}
			if($codeUser !=0 && $codeUser==$userID)
			{
				echo_debug('CODE code_checkCodeUser | return TRUE<br>');
				$check= TRUE;
			}
			else
			{
				echo_debug('CODE code_checkCodeUser | return FALSE - This code does not belong to the user<br>');
				$check=FALSE;			
			}
		}
		else
		{
			echo_debug('CODE code_checkCodeUser | return FALSE Code not found<br>');
			$check=FALSE;			
		}
		
		return $check;
	}
	
	//return the status of a code 
	function code_CodeStatus($bdd, $CodeID)
	{
		echo_debug('CODE code_CodeStatus | codeID='.$CodeID.'<br>');
		try
		{  
			$response = $bdd->prepare('SELECT status FROM code WHERE id=:ci');
			$response->execute(array(
				'ci' => $CodeID
			));
		}	
		catch (Exception $e)
		{
			die('Error : '.$e->getMessage());
		}
		
		try
		{
			$nb_rows = $response->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		$codeStatus="";
		if($nb_rows > 0)
		{
			while ($data = $response->fetch())
			{
				$codeStatus = $data['status']; 	
			}
		}
		echo_debug('CODE code_CodeStatus | return codeStatus='.$codeStatus.'<br>');
		return $codeStatus;
	}
?>