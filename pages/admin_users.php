<script type="text/javascript">
function unhide(divID) {
    var item = document.getElementById(divID);
    if (item) {
      item.className=(item.className=='admHidden')?'admUnhidden':'admHidden';
    }
}
</script>
<section>
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
		
		echo('<a href="javascript:unhide(\'UsersList\');">Display list of users / Hide</a>');
		echo('<div id="UsersList" class="admHidden">');
		echo('<table style="width:600px">');
				foreach ($users as $auser)
				{
					echo ("<tr><td>".$auser->getid()."</td><td>".$auser->getfirstname()."</td><td>".$auser->getlastname()."</td><td>".$auser->getregistrationdate()."</tr>");
				}
		echo('</table></div>');
	?>
</section>