<h2>Users</h2>
	<p>Number of registered user = 
	<?php 
		require('controler/bdd.php');
		require('controler/admin.php');
		echo admin_getnbusers($bdd);
	?> 
	</p>
	<p>List of users
	<?php
		require('model/user.php');
		
		$users = admin_getallusers($bdd);
		
		echo ("<table style=\"width:600px\">");
			foreach ($users as $auser)
			{
				echo ("<tr><td>".$auser->getid()."</td><td>".$auser->getfirstname()."</td><td>".$auser->getlastname()."</td><td>".$auser->getregistrationdate()."</tr>");
			}
		
	?>
	</p>