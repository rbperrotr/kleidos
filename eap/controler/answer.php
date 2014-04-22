<?php
	
	//Get answer from 1 question
	function answer_getEnigmaAnswer($enigmaID, $bdd)
	{
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
	function answer_already_answered($bdd, $enigmaID, $userID) 
	{
		$query = $bdd->prepare('SELECT count(submitted_answers.id) as total from submitted_answers, enigma where submitted_answers.enigma_ID=enigma.id and submitted_answers.answer=enigma.expected_answer and submitted_answers.user_id=:ui');	
		$query->execute(array(
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
			while ($donnees = $query->fetch())
			{
				$total = ($donnees['total']);
				echo_debug("answer_already_answered | number of good answer=".$total."<br/>");
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
	
	//Saving user answer
	function answer_saveAnswer($bdd, $enigmaID, $userID, $answer)
	{
		$query = $bdd->prepare('INSERT INTO submitted_answers (enigma_id, user_id, answer, date_time) VALUES (:ei, :ui, :ua, NOW())');
		$query->execute(array(
				'ei' => $enigmaID,
				'ui' => $userID,
				'ua' => $answer
		));
		//prepare email for notification to the guardians
		$new_line = "\r\n";
		$header = "From: \"The guardians\"<guardians@kleidos.tk>".$new_line;
		$header.= "Reply-to: \"The guardians\" <guardians@kleidos.tk>".$new_line;
		$header.= "MIME-Version: 1.0".$new_line;
		$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;

		$to="Guardians@kleidos.tk";
		$subject="NEW ANSWER submitted by ".$firstname." ".$lastname;
		$now=date('Y-m-d H:i:s');
		$message="A user has submitted an answer for enigma ".$enigmaID.$now.$new_line;
		$message.="Submitted answer ".$answer;
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
?>