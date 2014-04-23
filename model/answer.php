<?php
	class Answer
	{
		private $id;
		private $enigmaID;
		private $text;
		
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
	}
	
?>