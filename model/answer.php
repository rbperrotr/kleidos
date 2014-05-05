<?php
	class Answer
	{
		private $id;
		private $enigmaID;
		private $text;
		private $date_time;
		private $correct_answer;
		
		function Answer($n_id, $n_enigmaID, $n_text)
		{
			$this->id = $n_id;
			$this->enigmaID = $n_enigmaID;
			$this->text = $n_text;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($newID)
		{
			$this->id - $newID;
		}
		
		public function getEnigmaID()
		{
			return $this->enigmaID;
		}
		
		public function setEnigmaID($newEnigmaID)
		{
			$this->enigmaID = $newEnigmaID;
		}
		
		public function getText()
		{
			return $this->text;
		}
		
		public function setText($newText)
		{
			$this->text = $newText;
		}
		
		public function getDateTime()
		{
			echo_debug("ANSWER CLASS | getDateTime=".$this->date_time."<br>");
			return $this->date_time;
		}
		
		public function setDateTime($newDateTime)
		{
			$this->date_time = $newDateTime;
		}
	}
	
	class Answer_for_report extends	Answer
	{
		private $firstname;
		private $lastname;
		private $enigma_title;
		
		function Answer_for_report($n_answer_id, $n_enigmaID, $n_answer, $n_answer_date, $n_firstname, $n_lastname, $n_enigma_title)
		{
			$this->Answer($n_answer_id, $n_enigmaID, $n_answer);
			$this->setDateTime($n_answer_date);
			$this->firstname = $n_firstname;
			$this->lastname = $n_lastname;
			$this->enigma_title = $n_enigma_title;
		}
		
		public function getFirstname()
		{
			return $this->firstname;
		}
		
		public function setFirstname($newFirstname)
		{
			$this->firstname = $newFirstname;
		}
		
		public function getLastname()
		{
			return $this->lastname;
		}
		
		public function setLastname($newLastname)
		{
			$this->lastname = $newLastname;
		}
		
		public function getEnigmaTitle()
		{
			return $this->enigma_title;
		}
		
		public function setEnigmaTitle($newEnigmaTitle)
		{
			$this->enigma_title = $newEnigmaTitle;
		}
	}
	
	class Answer_for_report_by_enigma
	{
		private $enigmaID;
		private $enigmaTitle;
		private $nbAnswers;
		
		function Answer_for_report_by_enigma($n_enigma_id, $n_enigma_title, $n_nb_answers)
		{
			$this->enigmaID = $n_enigma_id;
			$this->enigmaTitle = $n_enigma_title;
			$this->nbAnswers = $n_nb_answers;
		}
		
		public function getEnigmaID()
		{
			return $this->enigmaID;
		}
		
		public function setEnigmaID($newEnigmaID)
		{
			$this->enigmaID = $newEnigmaID;
		}
		
		public function getEnigmaTitle()
		{
			return $this->enigmaTitle;
		}
		
		public function setEnigmaTitle($newEnigmaTitle)
		{
			$this->enigmaTitle = $newEnigmaTitle;
		}
		
		public function getNbAnswers()
		{
			return $this->nbAnswers;
		}
		
		public function setNbAnswers($newNbAnswers)
		{
			$this->NbAnswers = $newNbAnswers;
		}

		public function getCorrectAnswer()
		{
			return $this->correctAnswer;
		}
		
		public function setCorrectAnswer($newCorrectAnswer)
		{
			$this->CorrectAnswer = $newCorrectAnswer;
		}
		
	}
?>