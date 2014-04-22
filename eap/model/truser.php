<?php
	class TRUser
	{
		private $id;
		private $email;
		private $safeID;
		
		
		function TRUser($n_id,$n_email, $n_safeID)
		{
			$this->id = $n_id;
			$this->email = $n_email;
			$this->safeID = $n_safeID;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($newID)
		{
			$this->id = $newID;
		}
		
		public function getEmail()
		{
			return $this->email;
		}
		
		public function setEmail($newEmail)
		{
			$this->email = $newEmail;
		}
		
		public function getSafeID()
		{
			return $this->safeID;
		}
		
		public function setSafeID($newSafeID)
		{
			$this->safeID = $newSafeID;
		}
	}
?>