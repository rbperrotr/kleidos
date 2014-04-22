<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos - My account</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		<?php
			require('controler/bdd.php');
			require('controler/global.php');
			require('controler/code.php');
			
			includeBanner();
		?>
		<h1>My account</h1>
			
		<br>
		Below are the codes you already won which are still available.<br>
		Remember that those codes are private, then only you can use them.<br>
		<p><strong>
		<?php
			//list all codes available
			$codeText = code_assignedcodes($bdd, $_SESSION['uid']);
			
			foreach ($codeText as $code)
				{
					echo $code." ";
				}
		?>		
		</strong></p>
		<footer>
			<a href="mailto:guardians@kleidos.tk">Contact the guardians</a>.
		</footer>	
	</body>
</html>