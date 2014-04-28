<script type="text/javascript">
function toggleMe(a){
	var e=document.getElementById(a);
	if(!e)return true;
	
	if(e.style.display=="none"){
		e.style.display="block"
	}
	else{
	e.style.display="none"
	}
	return true;
}
</script>
<h2>Users</h2>
	<p>Number of registered user = 
	<?php 
		require('controler/bdd.php');
		require('controler/admin.php');
		echo admin_getnbusers($bdd)-3;
		echo " (not including the 3 guardians)";
	?> 
	</p>
	<h3>List of users</h3>
	<?php
		require('model/user.php');
		
		$users = admin_getallusers($bdd);
		
		echo("<input type=\"button\" class=\"enigmaButton\" onclick=\"return toggleMe('AllUsers')\" value=\"Collapse/Expand list of users\"><br>");
		
		echo ("<table id=\"AllUsers\" style=\"width:600px\">");
			foreach ($users as $auser)
			{
				echo ("<tr><td>".$auser->getid()."</td><td>".$auser->getfirstname()."</td><td>".$auser->getlastname()."</td><td>".$auser->getregistrationdate()."</tr>");
			}
		
	?>
	</p>