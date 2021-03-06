	<script type="text/javascript">
function unhide(divID) {
    var item = document.getElementById(divID);
    if (item) {
      item.className=(item.className=='admHidden')?'admUnhidden':'admHidden';
    }
}
</script>

<section>
<h2>Answers</h2>
	<p>Number of collected answers  = 
	<?php 
		echo admin_getnbanswers($bdd); // this is not counting the tests answers submitted by the guardians
		echo " (not including the ones submitted by the 3 guardians)";
	?> 
	</p>
	<h3>Number of collected answers by enigma</h3>  
	<p>(not including the ones submitted by the 3 guardians)<br>
	<div>
	<?php 
		$answers = admin_getnbanswers_byenigma($bdd);
		echo ("<table>");
		foreach ($answers as $ananswer)
		{
			echo ("<tr>");
			echo("<td style=\"white-space: nowrap\">".$ananswer->getEnigmaTitle()." | </td>");
			echo("<td style=\"white-space: nowrap\">".$ananswer->getNbAnswers()."</td>");
			echo("<td style=\"white-space: nowrap\"> | ".admin_getnbcorrectanswers($bdd, $ananswer->getEnigmaID())." correct(".number_format(100*(admin_getnbcorrectanswers($bdd, $ananswer->getEnigmaID())/$ananswer->getNbAnswers()))."%)</td><td> | <b>".number_format(100*(admin_getnbcorrectanswers($bdd, $ananswer->getEnigmaID())/(admin_getnbusers($bdd)-3)))."% of players answered correctly</b></td>");
			echo("</tr>");
		}
		
		echo("</table>");
	?> 
	</div>
	</p>

	<h3>List of answers</h3>
	<?php
		$now = date('Y-m-d H:i:s');	
		$now = new DateTime($now);
		$now = $now->format('d-M-Y H:i:s e');
		echo("Current time on server: ".$now."<br>");
		$answers = admin_getallanswers($bdd);
		
		echo('<a href="javascript:unhide(\'AnswersList\');">Display/hide list of answers</a>');
		echo('<div id="AnswersList" class="admHidden">');
		
		echo ("<table style=\"width:600px\">");
		foreach ($answers as $ananswer)
		{
			echo ("<tr>");
			echo("<td style=\"white-space: nowrap\">".$ananswer->getId()." | </td>");
			echo("<td style=\"white-space: nowrap\">".$ananswer->getEnigmaId()." | </td>");
			echo("<td style=\"width:300px; white-space: nowrap\">".$ananswer->getEnigmaTitle()." | </td>");
			if(strlen($ananswer->getText())>30)
				$answer_text=substr($ananswer->getText(),0,30)."(...)";
			else
				$answer_text=$ananswer->getText();
			echo("<td style=\"width:300px; white-space: nowrap\">".$answer_text."</td>");
			echo("<td style=\"width:300px; white-space: nowrap\"> | ".$ananswer->getDateTime()." | </td>");
			echo("<td style=\"width:250px; white-space: nowrap\">".$ananswer->getFirstname()." ".$ananswer->getLastname()."</td>");
			echo("</tr>");
		}
		
		echo("</table></div>");
	?>
	<h3>Notification to the guardians on all answers</h3>
	<form method="post" action="index.php">
	<p>
		<?php
			if(isset($_SESSION['ANSWERS_NOTIF']))
			{
				if($_SESSION['ANSWERS_NOTIF']==1)
				{
					echo "<input type=\"checkbox\" name=\"checkbox_answers_notif\" id=\"checkbox_answers_notif\" checked=\"checked\"/> <label for=\"checkbox_answers_notif\">Enabled</label>";
				}
				else{
					echo "<input type=\"checkbox\" name=\"checkbox_answers_notif\" id=\"checkbox_answers_notif\"/> <label for=\"checkbox_answers_notif\">Enabled</label>";
				}
			}
			else
			{
				$_SESSION['ANSWERS_NOTIF']=0;
				echo "<input type=\"checkbox\" name=\"checkbox_answers_notif\" id=\"checkbox_answers_notif\"/> <label for=\"checkbox_answers_notif\">Enabled</label>";
			}
		?>
	</p>
	<input type="hidden" name="action" value="answers_notif" />
	<input class="stdButton" type="submit" value="Submit"/>
	</form>
</section>
