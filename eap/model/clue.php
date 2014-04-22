<?php
	class Clue
	{
		private $id;
		private $text;
		private $enigmaID;
		private $sortID;
		private $publishedDate;
		
		
		function Clue($n_id,$n_text, $n_enigmaID, $n_order, $n_publishedDate)
		{
			$this->id = $n_id;
			$this->text = $n_text;
			$this->enigmaID = $n_enigmaID;
			$this->sortID = $n_order;
			$this->publishedDate = $n_publishedDate;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($newID)
		{
			$this->id = $newID;
		}
		
		public function getText()
		{
			return $this->text;
		}
		
		public function setText($newText)
		{
			$this->text = $newText;
		}
		
		public function getEnigmaID()
		{
			return $this->enigmaID;
		}
		
		public function setEnigmaID($newEnigmaID)
		{
			$this->enigmaID = $newEnigmaID;
		}
		
		public function getsortID()
		{
			return $this->sortID;
		}
		
		public function setsortID($newsortID)
		{
			$this->sortID = $newsortID;
		}
		
		public function getpublishedDate()
		{
			return $this->publishedDate;
		}
		
		public function setpublishedDate($newpublishedDate)
		{
			$this->publishedDate = $newpublishedDate;
		}
	}
?>