<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos</title>
		<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
    </head>
	
	<body>
		<?php
			require('controler/bdd.php');
			require('controler/global.php');
			require('model/user.php');
			require('controler/user.php');
			require('model/enigma.php');
			require('controler/enigma.php');
			require('controler/answer.php');
			if(checkLogin() == true)
			{
				includeBanner();
			}
			else
			{
				header("Location: index.php");
				exit;
			}
			
			$enigmas = enigma_getViewableEnigmas($bdd);
		?>
		<h1>Eikon Kleidos</h1>
		<p>Are you ready to start?</p>
		<p>Here are the enigmas, they will be published regularly.</p>	
		<p>
			<?php
				foreach ($enigmas as $enigma)
				{
					$nbDays=answer_nbDays_since_publication($bdd, $enigma->getID(), $_SESSION['uid']);
					echo_debug("ENIGMAS | analyse nb of days if good answer (nbdays=".$nbDays.")<br>");
					if ($nbDays == 0)
					{
						echo "<a href='enigma.php?ref=".$enigma->getRef()."'><button class=\"enigmaButtonDoubleGold\" title=\"Double gold coin answer: I got two hint codes for this.\"><img style='height:17px;' src='resources/".$enigma->getPicture()."'/> ".$enigma->getTitle()."</button></a><br />";
					}
					elseif ($nbDays == 1)
					{
						echo "<a href='enigma.php?ref=".$enigma->getRef()."'><button class=\"enigmaButtonGold\" title=\"Gold coin answer: I got one hint code for this.\"><img style='height:17px;' src='resources/".$enigma->getPicture()."'/> ".$enigma->getTitle()."</button></a><br />";
					}
					elseif ($nbDays > 1)
					{
						echo "<a href='enigma.php?ref=".$enigma->getRef()."'><button class=\"enigmaButtonSilver\" title=\"Silver coin answer: I didn't get any hint code for this.\"><img style='height:17px;' src='resources/".$enigma->getPicture()."'/> ".$enigma->getTitle()."</button></a><br />";
					}
					else
					{
						echo "<a href='enigma.php?ref=".$enigma->getRef()."'><button class=\"enigmaButton\" ><img style='height:17px;' src='resources/".$enigma->getPicture()."'/> ".$enigma->getTitle()."</button></a><br />";
					}			
				}
			?>
		</p>
		<!--/form-->
		<?php
				includeFooter();
		?>
	</body>
</html>