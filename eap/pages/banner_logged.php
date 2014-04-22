<div class="banner">
	<div class="banner_left">
		<img src="resources/small logo.png" height="40"/>
		<a href='index.php'><button class="navButton" >Introduction</button></a>
		<a href='enigmas.php'><button class="navButton" >Enigmas</button></a>
	</div>
	<div class="banner_right">	
			Hello <?php echo $_SESSION['login'];?>!
			<?php
				if($_SESSION['uid']==3 or $_SESSION['uid']==4 or $_SESSION['uid']==5)
				{
					echo " [<a href=admin.php>Administration</a>]";
				}
			?>
			[<a href="user_account.php">My account</a>]
			<form class="inline" action="index.php" method="post">
				<input type="hidden" name="action" value="logout" />
				<input class="bannerButton" type="submit" value="Logout" />
			</form>
	</div>
</div>