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
				<input class="stdButton" type="submit" value="Submit">
			</form><br/>
			<?php
				if(isset($_POST['answer']))
				{
					echo_debug("ENIGMA | Start answer analyse<br>");
					$answers = answer_getEnigmaAnswer($enigma->getId(), $bdd);
					$u_answer = strtoupper(htmlspecialchars($_POST['answer']));
					
					echo_debug("enigma | user answer = ".$u_answer."<br/>");
					if (answer_get_time_box_previous_answers($bdd, $enigma->getId(), $_SESSION['uid']))
					{
						$good_answer = false;
						foreach($answers as $answer)
						{
							if($answer->getText() == $u_answer)
							{
								$good_answer = true;
								echo_debug("enigma | user answer = ".$u_answer." recognized as CORRECT<br/>");
							}
						}
						
						if($good_answer == true)
						{
							if($enigma->getRef()=="PRAEPO")
								{
									echo_debug("Time to conclude<br>");
									//include("pages/praepossum.php");
									echo ('<script type="text/javascript">window.open("./pages/praepossum.php", "_self")</script>');
								}
							elseif(answer_already_answered($bdd, $enigma->getId(), $_SESSION['uid']))
							{
								echo "you already answered this enigma!<br/> As a reminder the correct answer is <span class=\"correct_answer\">".$enigma->getExpected_answer()."</span><br>";
							}
							else
							{
								answer_saveAnswer($bdd, $enigma->getId(), $_SESSION['uid'], $u_answer, 'YES');
								// if $now<publishedDate clue#1 give 2 codes
								// if $now<publishedDate clue#2 give 1 code
								// else don't give any code
								$today = date('Y-m-d');	
								$today = new DateTime($today);
								$today = $today->format('Ymd His');
								if($today<clue_getClueOnePublicationDate($bdd, $enigma->getId(),1))
								{
									$nbcode=2;
									echo_debug("ENIGMA | will give two codes<br>");
									$code1 = code_getANewCode($bdd);
									code_assignCode($bdd, $_SESSION['uid'], $enigma->getId(), $code1->getId());	
									$code2 = code_getANewCode($bdd);
									code_assignCode($bdd, $_SESSION['uid'], $enigma->getId(), $code2->getId());	
									echo "<article><span class=\"correct_answer\">Congratulations! <br/> The correct answer is:  <span class=\"correct_answer\"> ".$enigma->getExpected_answer()."</span><br/> Thanks to your this right answer you won a hint code: ".$code1->getText().".<br/>You have been so quick to answer than you even win a bonus code: ".$code2->getText()."<br> Keep them carefully as you may want to use them later. </div></article>";
								}
								elseif($today<clue_getClueOnePublicationDate($bdd, $enigma->getId(),2))
								{
									$nbcode=1;
									echo_debug("ENIGMA | will give one codes<br>");
									$code1 = code_getANewCode($bdd);
									code_assignCode($bdd, $_SESSION['uid'], $enigma->getId(), $code1->getId());	
									echo "<article><div class=\"correct_answer\">Congratulations! <br/> The correct answer is:  <span class=\"strong_correct_answer\"> ".$enigma->getExpected_answer()."</span><br/> Thanks to this right answer you won a hint code: ".$code1->getText().".<br/> Keep it carefully as you may want to use it later. </div></article>";
								}
								else
								{
									$nbcode=0;
									$congratulationMessage="";
									echo_debug("ENIGMA | will not give any codes<br>");
									echo "<article><div class=\"correct_answer\">Congratulations! <br/> The correct answer is:  <span class=\"strong_correct_answer\"> ".$enigma->getExpected_answer()."</span><br/> Stay tuned to see new enigmas and get code by answering before hints are published.<br/></div></article>";
								}
							}
						}
						else
						{
							echo_debug("enigma | user answer = ".$u_answer." NOT recognized as CORRECT<br/>");
							answer_saveAnswer($bdd, $enigma->getId(), $_SESSION['uid'], $u_answer, 'NO');
							echo "<article><div class=\"wrong_answer\">Sorry, this is not a good answer, try again.<br>You can submit up to 5 answers per hour.</div></article>";
						}
					}
					else
					{
						echo "<article><div class=\"wrong_answer\">Sorry, you have already submitted 5 answers in the last hour, please try again later.<br>You can submit up to 5 answers per hour.</div></article>";
					}
				}
				elseif(isset($_POST['cluecode']))
				{
					$userID=$_SESSION['uid'];
					echo_debug("ENIGMA | Start buy clue<br>");
					$codetxt = htmlspecialchars($_POST['cluecode']);
					echo_debug("ENIGMA BUY CLUE | for enigmaID=".$enigma->getID()." userID=".$userID." with code=".$codetxt."<br>");
					//1-get codeID from codeText
					//2-check code exists
					//3-check code ID belongs to the user
					//4-check code ID is available
					//5-check a hint is available for the current enigma 
					//5-buy a clue for the codeID
					
					//1-get codeID from codeText
					echo_debug("ENIGMA BUY CLUE | Check codeText by searching for the associated code ID <br>");
					$codeID = code_getcodeID($bdd, $codetxt);
					//2-check code exists
					if($codeID==0)
					{
						echo '<div class=\"error_message\"><strong>Invalid code, please check on MyAccount page.</strong></div>';
					}
					elseif(!code_checkCodeUser($bdd, $codeID, $userID)) //3-check code ID belongs to the user
					{
						echo '<div class=\"error_message\"><strong>Invalid code, this code does not belong to you, please check on MyAccount page.</strong></div>';
					}
					elseif(code_CodeStatus($bdd, $codeID)=='Used') //4-check code ID is available
					{
						echo '<div class=\"error_message\"><strong>Invalid code, this code has been used already, please check on MyAccount page.</strong></div>';
					}
					elseif(code_CodeStatus($bdd, $codeID)=='New')
					{
						echo '<div class=\"error_message\"><strong>Invalid code, please check on MyAccount page.</strong></div>';
					}
					//6-buy a clue for the codeID
					//OLD from MD PoC code_useCode($bdd, $codeID, $_SESSION['uid']);
					elseif(code_buyAHint($bdd, $codeID, $enigma->getID(), $userID)==-1)
					{
						echo_debug('ENIGMA BUY CLUE | No hint bought');
						echo '<div class=\"error_message\"><strong>Your code is valid but there is currently no hint to buy for this enigma.</strong></div>';
					}
					else
					{
						echo_debug('ENIGMA BUY CLUE | Hint bought');
					}
					
				}
			?>
		</article>
		
		<section>
			<?php
				//Display all codes ready published for the enigma
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
						echo "<li><span class='enigmaHint'>".$clue->getText()."</span></li>";
					}
					
				}
				//Display all additional codes not yet published for the enigma but bought with codes
				$boughtClues = clue_getCluesBoughtByUserNotYetPublished($bdd, $enigma->getId(), $_SESSION['uid']);
				$nbBoughtClues = count($boughtClues);
				if($nbBoughtClues>0)
				{
					foreach($boughtClues as $aBoughtclue)
					{
						echo '<li><span class="enigmaHint">'.$aBoughtclue.'</span><span class="correct_answer"> (<img src="resources/done_gold.png" style="width:20px; vertical-align:middle;">not yet public, bought with a code you won).</span></li>';
					}
					echo "</ul>";
				}
				if($nbclues+$nbBoughtClues >0)
					echo "</ul>";
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