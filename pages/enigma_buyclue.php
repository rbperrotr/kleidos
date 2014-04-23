<?php
	//echo "Where am I? BuyClue";	
?>
<?php
	//require('controler/bdd.php');
	//require('controler/global.php');
	//require('model/enigma.php');
	//require('controler/enigma.php');
?>		
		
<br>
<h2>Buy a hint: </h2>
<p>If you won a hint code by answering correctly to a previous enigma then you can buy a clue to get it in advance.</p>
<form action="TBD" method="post">
	Enter your code <input type="text" name="cluecode">
	<input class="MyButton" type="submit" value="Submit">
</form>
<br>
<?php
	if(isset($_POST['cluecode']))
	{
		$codetxt = htmlspecialchars($_POST['cluecode']);
		$codeID = code_getcodeID($bdd, $codetxt);
		code_useCode($bdd, $codeID, $_SESSION['uid']);
	}
?>
		