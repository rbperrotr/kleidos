<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Kleidos</title>
		<meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
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
			require('model/code.php');
			require('controler/code.php');
			require('model/clue.php');
			require('controler/clue.php');
			require('model/answer.php');
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
			
			if(isset($_GET['ref']))
			{
				$eID = htmlspecialchars($_GET['ref']);
			}
			else
			{
				header("Location: index.php");
				exit;
			}
			
			$enigma = enigma_getEnigma($bdd, $eID);
		
		?>
		<div id="enigma_wrapper">
			<h1> <?php echo $enigma->getTitle(); ?> </h1>
			<aside class="enigma_picture_aside">
				<img src="resources/<?php echo $enigma->getPicture(); ?>" width="200"/>
			</aside>
			<article>
				<p> <?php echo $enigma->getText(); ?></p><br/>
			</article>
		</div>
		<article class="article_fullwidth">
			<form class="centered" action="enigma.php?ref=<?php echo $eID?>" method="post">
				Enter your answer <input type="text" name="answer">
				<input class="MyButton" type="submit" value="Submit">
			</form><br/>
			<?php
				if(isset($_POST['answer']))
				{
					$answers = answer_getEnigmaAnswer($enigma->getId(), $bdd);
					$u_answer = strtoupper(htmlspecialchars($_POST['answer']));
					echo_debug("enigma | answer = ".$u_answer."<br/>");
					if (answer_get_time_box_previous_answers($bdd, $enigma->getId(), $_SESSION['uid']))
					//if (answer_get_time_box_previous_answers($bdd, $enigma->getId(), $_SESSION['uid'] == true))
					//Test to fix the issue on the nb of try, is the closing bracket on the right place? RBP suspect NO, if this is correct the test agains true is useless
					{
						//answer_saveAnswer($bdd, $enigma->getId(), $_SESSION['uid'], $u_answer);
						
						$good_answer = false;
						foreach($answers as $answer)
						{
							if($answer->getText() == $u_answer)
							{
								$good_answer = true;
							}
						}
						
						if($good_answer == true)
						{
							if(answer_already_answered($bdd, $enigma->getId(), $_SESSION['uid']))
							{
								echo "you already answered this enigma!<br/> As a reminder the correct answer is <span class=\"correct_answer\">".$enigma->getExpected_answer()."</span><br>";
							}
							else
							{
								answer_saveAnswer($bdd, $enigma->getId(), $_SESSION['uid'], $u_answer);
								$code = code_getANewCode($bdd);
								code_assignCode($bdd, $_SESSION['uid'], $enigma->getId(), $code->getId());
								
								echo "<article><div class=\"correct_answer\">Congratulations! <br/> The correct answer is:  <span class=\"correct_answer\"> ".$enigma->getExpected_answer()."</span><br/> Thanks to this right answer you won a hint code: ".$code->getText().".<br/> Keep it carefully as you may want to use it later. </div></article>";
							}
						}
						else
						{
							answer_saveAnswer($bdd, $enigma->getId(), $_SESSION['uid'], $u_answer);
							echo "<article><div class=\"wrong_answer\">Sorry, this is not a good answer, try again.<br>You can submit up to 5 answers per hour.</div></article>";
						}
					}
					else
					{
						echo "<article><div class=\"wrong_answer\">Sorry, you have already submitted 5 answers in the last hour, please try again later.<br>You can submit up to 5 answers per hour.</div></article>";
					}
				}
			?>
		</article>
		
		<section>
			<?php 
				/* Code from Matthieu to display hints
				$clues = clue_getCluesfromEnigma($bdd, $enigma->getId());
				$uClues = clue_getCluesofUser($bdd, $enigma->getId(), $_SESSION['uid'], $clues);
				foreach($uClues as $uClue)
				{
					echo "<br />".$uClue->getText()."<br />";
				}
				*/
			?>
			<?php
				/* replace code from Matthieu D above, but does not yet manage the hint bought by a user with code */
				$clues = clue_getCluesToPublish ($bdd, $enigma->getId());
				$nbclues = count($clues);
				if($nbclues>0)
				{
					echo "<br>";
					if($nbclues>1)
					{
						echo "<h2>Hints:</h2>";
						echo "<ul>";
					}
					else
					{
						echo "<h2>Hint:</h2>";
					}
					
					foreach($clues as $clue)
					{
						echo "<p><li>".$clue->getText()."</li></p>";
					}
					echo "</ul>";
				}
			?>
			<?php
			if ($enigma->getBuyClue()==true)
				{
					includeBuyClue();
				}
			?>
		</section>
		
		<?php
			includeFooter();
		?>
	</body>
</html>