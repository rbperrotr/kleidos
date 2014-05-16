<?php
	class Enigma
	{
		private $id;
		private $title;
		private $text;
		private $nbClue;
		private $publiDate;
		private $ref;
		private $picture;
		private $expected_answer;
		private $buy_clue;	
		private $assign_code;
		
		function Enigma($n_id, $n_title, $n_text, $n_nbClue, $n_publiDate, $n_ref, $n_picture, $n_expected_answer, $n_buy_clue, $n_assign_code)
		{
			$this->id = $n_id;
			$this->title = $n_title;
			$this->text = $n_text;
			$this->nbClue = $n_nbClue;
			$this->publiDate = $n_publiDate;
			$this->ref = $n_ref;
			$this->picture = $n_picture;
			$this->expected_answer = $n_expected_answer;
			$this->buy_clue = $n_buy_clue;
			$this->assign_code = $n_assign_code;
			echo_debug('MODEL ENIGMA | Constructor n_assign_code='.$n_assign_code.'.<br>');
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($newID)
		{
			$this->id = $newID;
		}
		
		public function getTitle()
		{
			return $this->title;
		}
		
		public function setTitle($newTitle)
		{
			$this->title = $newTitle;
		}
		
		public function getText()
		{
			return $this->text;
		}
		
		public function setText($newText)
		{
			$this->text = $newText;
		}
		
		public function getNbClue()
		{
			return $this->nbClue;
		}
		
		public function setNbClue($newNbClue)
		{
			$this->nbClue = $newNbClue;
		}
		
		public function getPubliDate()
		{
			return $this->publiDate;
		}
		
		public function setPubliDate($newPubliDate)
		{
			$this->publiDate = $newPubliDate;
		}
		
		public function getRef()
		{
			return $this->ref;
		}
		
		public function setRef($newRef)
		{
			$this->ref = $newRef;
		}
		
		public function getPicture()
		{
			return $this->picture;
		}
		
		public function setPicture($newPicture)
		{
			$this->picture = $newPicture;
		}
		
		public function getExpected_answer()
		{
			return $this->expected_answer;
		}
		
		public function setExpectedAnswer($newExpectedAnswer)
		{
			$this->expected_answer = $newExpectedAnswer;
		}
		
		public function getBuyClue()
		{
			return $this->buy_clue;
		}
		
		public function setBuyClue($newBuyClue)
		{
			$this->buy_clue = $newBuyClue;
		}		
		
		public function getAssignCode()
		{
			echo_debug('MODEL ENIGMA | getAssignCode'.$this->assign_code.'<br>');
			return $this->assign_code;
		}
		
		public function setAssignCode($newAssignCode)
		{
			$this->assign_code = $newAssignCode;
		}
	}
?>