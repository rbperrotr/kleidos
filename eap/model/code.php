<?php
	class Code
	{
		private $id;
		private $text;
		private $enigmaID;
		private $userID;
		private $clueID;
		private $status;
		private $usedDate;
		
		function Code($n_id,$n_text, $n_enigmaID, $n_userID, $n_clueID, $n_status, $n_usedDate)
		{
			$this->id = $n_id;
			$this->text = $n_text;
			$this->enigmaID = $n_enigmaID;
			$this->userID = $n_userID;
			$this->clueID = $n_clueID;
			$this->status = $n_status;
			$this->usedDate = $n_usedDate;
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
		
		public function getUserID()
		{
			return $this->userID;
		}
		
		public function setUserID($newUserID)
		{
			$this->userID = $newUserID;
		}
		
		public function getClueID()
		{
			return $this->clueID;
		}
		
		public function setClueID($newClueID)
		{
			$this->clueID = $newClueID;
		}
		
		public function getStatus()
		{
			return $this->status;
		}
		
		public function setStatus($newStatus)
		{
			$this->status = $newStatus;
		}
		
		public function getUsedDate()
		{
			return $this->usedDate;
		}
		
		public function setUsedDate($newUsedDate)
		{
			$this->usedDate = $newUsedDate;
		}
	}
	
?>