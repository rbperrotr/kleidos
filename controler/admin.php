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
		
		echo_debug("ADMIN | Start getallusers");
		
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
		
		/*
		private $id;
		private $login;
		private $firstname;
		private $lastname;
		private $password;
		private $safeID;
		private $registrationdate;
		*/
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
		return $users;
		
		echo_debug("ADMIN | Exit getallusers");
	}
?>