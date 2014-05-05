<?php
	
	//Get answer from 1 question
	function answer_getEnigmaAnswer($enigmaID, $bdd)
	{
		echo_debug("answer_getEnigmaAnswer for enigmaID=".$enigmaID."<br>");
		$reponse = $bdd->query('SELECT * FROM answer where enigmaId ='.$enigmaID);
		
		$answers = array();
		
		while ($donnees = $reponse->fetch())
		{
			$answer = new Answer($donnees['id'], $donnees['enigmaID'], $donnees['text']);
			$answers[] = $answer;
		}
		
		return $answers;
	}
	
	//Did I already answer to this enigma correctly?
	function OLD_answer_already_answered($bdd, $enigmaID, $userID) 
	{
		echo_debug("ANSWER | Start answer_already_answered<br>");
		echo_debug("ANSWER | answer_already_answered: EnigmaID=".$enigmaID.", UserID=".$userID.".<br>");
		
		$query = $bdd->prepare('SELECT count(submitted_answers.id) as total from submitted_answers, enigma where submitted_answers.enigma_ID=enigma.id and enigma.id=:ei and submitted_answers.answer=enigma.expected_answer and submitted_answers.user_id=:ui');	
		
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID
		));
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("answer_already_answered | nbrows =".$nb_rows."<br/>");
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$total = ($data['total']);
				echo_debug("answer_already_answered | number of good answer (total)=".$total."<br/>");
			}
			if($total > 0)
			{
				$answer = true;
			}
			else
			{
				$answer = false;
			}
		}
		else
		{
			$answer = false;
		}
		echo_debug("ANSWER | answer_already_answered return ".$answer."<br>");
		return $answer;
	}
	function answer_already_answered($bdd, $enigmaID, $userID) 
	{
		echo_debug("ANSWER | Start answer_already_answered<br>");
		echo_debug("ANSWER | answer_already_answered: EnigmaID=".$enigmaID.", UserID=".$userID.".<br>");
		
		$query = $bdd->prepare('SELECT count(id) AS total FROM submitted_answers WHERE correct_answer="YES" AND enigma_id=:ei AND user_id=:ui');	
		
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID
		));
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("answer_already_answered | nbrows =".$nb_rows."<br/>");
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$total = ($data['total']);
				echo_debug("answer_already_answered | number of good answer (total)=".$total."<br/>");
			}
			if($total > 0)
			{
				$answer = true;
			}
			else
			{
				$answer = false;
			}
		}
		else
		{
			$answer = false;
		}
		echo_debug("ANSWER | answer_already_answered return ".$answer."<br>");
		return $answer;
	}
	
	//Did I already answer to this enigma but not correctly?
	function answer_already_answered_without_success($bdd, $enigmaID, $userID) 
	{
		echo_debug("ANSWER | Start answer_already_answered_without_success<br>");
		echo_debug("ANSWER | answer_already_answered_without_success: EnigmaID=".$enigmaID.", UserID=".$userID.".<br>");
		
		$query = $bdd->prepare('SELECT count(submitted_answers.id) as total from submitted_answers, enigma where submitted_answers.enigma_ID=enigma.id and enigma.id=:ei and submitted_answers.answer!=enigma.expected_answer and submitted_answers.user_id=:ui');	
		
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID
		));
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("answer_already_answered_without_success | nbrows =".$nb_rows."<br/>");
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$total = ($data['total']);
				echo_debug("answer_already_answered_without_success | number of good answer (total)=".$total."<br/>");
			}
			if($total > 0)
			{
				$answer = true;
			}
			else
			{
				$answer = false;
			}
		}
		else
		{
			$answer = false;
		}
		echo_debug("ANSWER | answer_already_answered_without_success return ".$answer."<br>");
		return $answer;
	}
	
	//Can I submit an answer or did I try the maximum number of times in the last hour?
	function answer_get_time_box_previous_answers($bdd, $enigmaID, $userID)
	{
		$time=strtotime("-1 hour"); 
		echo_debug("answer_get_time_box_previous_answers | H-1=".$time."<br/>");
		$final=date("Y-m-d H:i:s",$time);
		echo_debug("answer_get_time_box_previous_answers | final=".$final."<br/>");
		
		$query = $bdd->prepare('SELECT count(id) as total FROM submitted_answers WHERE enigma_id = :ei AND user_id = :ui AND date_time >= :dt');
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID,
				'dt' => $final
		));
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("answer_get_time_box_previous_answers | nbrows =".$nb_rows."<br/>");
		if($nb_rows > 0)
		{
			while ($donnees = $query->fetch())
			{
				$total = ($donnees['total']);
				echo_debug("answer_get_time_box_previous_answers | number of answer(s) in the last hour=".$total."<br/>");
			}
			if($total < 5)
			{
				$answer = true;
			}
			else
			{
				$answer = false;
			}
		}
		else
		{
			$answer = true;
		}
		
		return $answer;
	}
	
	//When did I answered?
	function answer_nbDays_since_publication($bdd, $enigmaID, $userID)
	{
		/*
		SELECT DATEDIFF(MIN(submitted_answers.date_time),enigma.publidate) FROM submitted_answers, enigma WHERE submitted_answers.user_id=3 AND  submitted_answers.enigma_id=7 AND  submitted_answers.enigma_id=enigma.id AND  submitted_answers.answer=enigma.expected_answer
		*/
		echo_debug("ANSWER | Start function answer_nbDays_since_publication for enigmaid=".$enigmaID." and userid=".$userID."<br>");
		$query = $bdd->prepare('SELECT DATEDIFF(MIN(submitted_answers.date_time),enigma.publidate) 	AS nbDays FROM submitted_answers, enigma WHERE submitted_answers.user_id=:ui AND submitted_answers.enigma_id=:ei AND  submitted_answers.correct_answer="YES" AND submitted_answers.enigma_id=enigma.id');
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID
		));
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("ANSWER | function answer_nbDays_since_publication | nbrows =".$nb_rows."<br/>");
		$nbDays=-2;
		if($nb_rows > 0)
		{
			echo_debug("ANSWER | function answer_nbDays_since_publication | analyse rows<br/>");
			while ($data = $query->fetch())
			{
				$tmpNbDays = ($data['nbDays']);
				if(!is_null($tmpNbDays))
					$nbDays=$tmpNbDays;
				echo_debug("ANSWER | function answer_nbDays_since_publication | analyse rows and return nbDays=".$nbDays."<br/>");
			}
		}
		else
		{
			echo_debug("ANSWER | function answer_nbDays_since_publication | no rows<br/>");
			$nbDays = -1;
		}
		echo_debug("ANSWER | function answer_nbDays_since_publication | number of day(s) since good answer=".$nbDays."<br/>");
		return $nbDays;
	}
	
	//Saving user answer
	function answer_saveAnswer($bdd, $enigmaID, $userID, $answer, $correct_answer)
	{
		echo_debug("function answer_saveAnswer");
		$query = $bdd->prepare('INSERT INTO submitted_answers (enigma_id, user_id, answer, date_time, correct_answer) VALUES (:ei, :ui, :ua, NOW(), :ca)');
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID,
				'ua' => $answer,
				'ca' => $correct_answer
		));
		//prepare email for notification to the guardians
		
		$fullname = user_getFullName($bdd, $userID);
		
		$new_line = "\r\n";
		$boundary = "-----=".md5(rand());
		$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
		$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
		$header.= "MIME-Version: 1.0".$new_line;
		$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

		$to="Guardians@kleidos.tk";
		$subject="NEW ANSWER submitted by ".$fullname;
		$now=date('Y-m-d H:i:s');
		$message=$fullname." has submitted an answer for enigma ".$enigmaID.$now.$new_line;
		$message.="Submitted answer ".$answer;
		if(!canemail())
		{
			echo_debug("New answer email not sent from localhost"); //catch hostname local to avoid error on mail
		}
		else
		{
			try
			{
				if(!mail($to , $subject , $message, $header));
			}	
			catch (PDOException $e)
			{
				echo_debug("New answer email not sent");
				die('Erreur : '.$e->getMessage());
			}
		}

	}
?>