<?php

	function admin_getnbusers($bdd)
	{
		echo_debug("<br>ADMIN | Start getnbusers<br>");
		
		$query = $bdd->prepare('SELECT COUNT(id) as nbusers FROM user ORDER BY id DESC');
		$query->execute(array());
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		if($nb_rows > 0)
		{
			while ($donnees = $query->fetch())
			{
				$nbusers = ($donnees['nbusers']);
				echo_debug("ADMIN | getnbusers =".$nbusers."<br>");
			}
		}
		else
		{
			$nbusers = 0;
		}
		
		
		echo_debug("ADMIN | Exit getnbusers<br>");
		return $nbusers;
		
	}
	
	
	function admin_getallusers($bdd)
	{
		//require_once('controler/global.php');
		
		echo_debug("ADMIN | Start getallusers<br>");
		
		$query = $bdd->prepare('SELECT id, firstname, lastname, registrationdate, login FROM user ORDER BY id DESC');
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
		
		$users = array();
		if($nb_rows > 0)
		{
			while ($donnees = $query->fetch())
			{
				$user = new User($donnees['id'], $donnees['login'], $donnees['firstname'], $donnees['lastname'],"","", $donnees['registrationdate'] );
				$users[] = $user;
			}
			echo_debug("ADMIN | getallusers =".$nb_rows."<br>");
		}
		echo_debug("ADMIN | Return getallusers<br>");
		return $users;
	}
	
	//return the total number of answers, removing the test answers collected from the guardians
	function admin_getnbanswers($bdd)
	{
		echo_debug("<br>ADMIN | Start getnbanswers<br>");
		
		/*
		SELECT COUNT(id) as nbAnswers 
		FROM submitted_answers 
		WHERE user_id != 3 AND user_id != 4 AND user_id != 5
		*/
		
		$query = $bdd->prepare('SELECT COUNT(id) as nbAnswers FROM submitted_answers WHERE user_id != 3 AND user_id != 4 AND user_id != 5');
		$query->execute(array());
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$nbAnswers = ($data['nbAnswers']);
				echo_debug("ADMIN | getnbusers =".$nbAnswers."<br>");
			}
		}
		else
		{
			$nbAnswers = 0;
		}
		
		
		echo_debug("ADMIN | Exit getnbanswers<br>");
		return $nbAnswers;
	}

	//return the total number of answers by enigma, removing the test answers collected from the guardians
	function admin_getnbanswers_byenigma($bdd)
	{
		echo_debug("<br>ADMIN | Start getnbanswers_byenigma<br>");
		
		/*
		SELECT 
			submitted_answers.enigma_id, 
			enigma.title, 
			count(submitted_answers.id) 
		FROM submitted_answers, enigma 
		WHERE submitted_answers.enigma_id=enigma.id AND submitted_answers.user_id != 3 AND submitted_answers.user_id != 4 AND submitted_answers.user_id != 5
		GROUP BY submitted_answers.enigma_id
		*/
		
		$query = $bdd->prepare('SELECT submitted_answers.enigma_id as enigma_id, enigma.title as enigma_title, count(submitted_answers.id) as nb_answers
		FROM submitted_answers, enigma WHERE submitted_answers.enigma_id=enigma.id AND submitted_answers.user_id != 3 AND submitted_answers.user_id != 4 AND submitted_answers.user_id != 5	GROUP BY submitted_answers.enigma_id');
		$query->execute(array());
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		$answers = array();
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$answer = new Answer_for_report_by_enigma($data['enigma_id'], $data['enigma_title'], $data['nb_answers']);
				$answers[] = $answer;
			}
		}		
		
		echo_debug("ADMIN | Exit getnbanswers by enigma<br>");
		return $answers;
	}	
	
	//return the total number of correct answers by enigma, removing the test answers collected from the guardians
	function admin_getnbcorrectanswers($bdd, $enigma_id)
	{
		echo_debug("<br>ADMIN | Start getnbcorrectanswers_byenigma<br>");
		
		/*
		SELECT nb_answers FROM 
			(SELECT 
				submitted_answers.enigma_id as enigma_id, 
				enigma.title as enigma_title, 
				count(submitted_answers.id) as nb_answers
			FROM submitted_answers, enigma 
			WHERE submitted_answers.enigma_id=enigma.id AND enigma.expected_answer=submitted_answers.answer
			GROUP BY submitted_answers.enigma_id)as A 
		WHERE A.enigma_id = 6
		*/
		
		$query = $bdd->prepare('SELECT nb_answers as nbCorrectAnswers FROM (SELECT submitted_answers.enigma_id as enigma_id, enigma.title as enigma_title, count(submitted_answers.id) as nb_answers FROM submitted_answers, enigma WHERE submitted_answers.enigma_id=enigma.id AND enigma.expected_answer=submitted_answers.answer GROUP BY submitted_answers.enigma_id)as A WHERE A.enigma_id = :ei');
		$query->execute(array(
			'ei' => $enigma_id
		));
			
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$nbCorrectAnswers = ($data['nbCorrectAnswers']);
			}
		}		
		
		echo_debug("ADMIN | Exit getnbcorrectanswers<br>");
		return $nbCorrectAnswers;
	}
	
	//return list of all answers
	function admin_getallanswers($bdd)
	{
		echo_debug("ADMIN | Start getallanswers<br>");
		
		/*
		SELECT 
			submitted_answers.id as answer_id, 
			submitted_answers.enigma_id as enigmaid,
			submitted_answers.answer as answer_Text,
			submitted_answers.date_time as answer_date,
 			user.firstname as firstname, 
			user.lastname as lastname  
		FROM submitted_answers, user, enigma 
		WHERE user.id=submitted_answers.user_id AND submitted_answers.enigma_id=enigma.id AND submitted_answers.user_id !=3 AND  submitted_answers.user_id !=4 AND  submitted_answers.user_id !=5
		ORDER BY date_time DESC
		*/
		
		$query = $bdd->prepare('SELECT submitted_answers.id as answer_id,  submitted_answers.enigma_id as enigmaid, enigma.title as enigma_title, submitted_answers.answer as answer_text, submitted_answers.date_time as answer_date, user.firstname as firstname, user.lastname as lastname  FROM submitted_answers, user, enigma WHERE user.id=submitted_answers.user_id AND submitted_answers.enigma_id=enigma.id AND submitted_answers.user_id !=3 AND  submitted_answers.user_id !=4 AND  submitted_answers.user_id !=5 ORDER BY date_time DESC');
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
		$answers = array();
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$answer = new Answer_for_report($data['answer_id'], $data['enigmaid'], $data['answer_text'], $data['answer_date'],$data['firstname'],$data['lastname'], $data['enigma_title']);
				$answers[] = $answer;
			}
			echo_debug("ADMIN | getallusers =".$nb_rows."<br>");
		}
		echo_debug("ADMIN | Start getallanswers<br>");
		return $answers;
	}
?>