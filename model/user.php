﻿<?php
	class User
	{
		private $id;
		private $login;
		private $firstname;
		private $lastname;
		private $password;
		private $safeID;
		private $registrationdate;
		private $emailNotif;
		private $emailOtherPlayers;
		
		function User($n_id, $n_login, $n_firstname, $n_lastname, $n_password, $n_safeID, $n_registrationdate)
		{
			$this->id = $n_id;
			$this->login = $n_login;
			$this->firstname = $n_firstname;
			$this->lastname = $n_lastname;
			$this->password = $n_password;
			$this->safeID = $n_safeID;
			$this->registrationdate = $n_registrationdate;
			$this->emailNotif = "Daily";
			$this->emailOtherPlayers = "Yes";
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
		
		public function getregistrationdate()
		{
			return $this->registrationdate;
		}
		
		public function setregistrationdate($newregistrationdate)
		{
			$this->registrationdate = $newregistrationdate;
		}
		
		public function getemailNotif()
		{
			return $this->emailNotif;
		}
		
		public function setemailNotif($newemailNotif)
		{
			$this->emailNotif = $newemailNotif;
		}
		
		public function getemailOtherPlayers()
		{
			return $this->emailOtherPlayers;
		}
		
		public function setemailOtherPlayers($newemailOtherPlayers)
		{
			$this->emailOtherPlayers = $newemailOtherPlayers;
		}
	}
?>