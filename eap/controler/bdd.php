<?php

	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=rbpe', 'root', '');
		//$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=7mqajsz0', '7mqajsz0', 'lccot1963');
		//$bdd = new PDO('mysql:host=mysql13.000webhost.com;dbname=a1464840_db', 'a1464840_db', 'kleidos2014');
	}
	catch (Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}

?>