<?php
	//echo "Where am I? BuyClue";	
?>
<?php
	require('controler/bdd.php');
	//require('controler/global.php');
	//require('model/enigma.php');
	//require('controler/enigma.php');
	if(isset($_GET['ref']))
	{
		$enigmaID = htmlspecialchars($_GET['ref']);
	}
?>		
		
<br>
<h2>Buy a hint: </h2>
<p>If you won a hint code by answering correctly to a previous enigma then you can buy a hint to get it in advance.</p>
<form action="enigma.php?ref=<?php echo $enigmaID?>" method="post">
	Enter your code <input type="text" name="cluecode">
	<input class="stdButton" type="submit" value="Submit">
</form>
<br>
	