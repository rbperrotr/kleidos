<?php
	class User
	{
		private $id;
		private $login;
		private $firstname;
		private $lastname;
		private $password;
		private $safeID;
		
		function User($n_id, $n_login, $n_firstname, $n_lastname, $n_password, $n_safeID)
		{
			$this->id = $n_id;
			$this->login = $n_login;
			$this->firstname = $n_firstname;
			$this->lastname = $n_lastname;
			$this->password = $n_password;
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
		
		public function getLogin()
		{
			return $this->login;
		}
		
		public function setLogin($newLogin)
		{
			$this->login = $newLogin;
		}
		
		public function getFirstName()
		{
			return $this->firstname;
		}
		
		public function setFirstName($newFirstName)
		{
			$this->firstname = $newFirstName;
		}
		
		public function getLastName()
		{
			return $this->lastname;
		}
		
		public function setLastName($newLastName)
		{
			$this->lastname = $newLastName;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
		
		public function setPassword($newPassword)
		{
			$this->password = $newPassword;
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