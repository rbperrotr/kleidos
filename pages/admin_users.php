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
		$now = date('Y-m-d H:i:s');	
		$now = new DateTime($now);
		$now = $now->format('d-M-Y H:i:s e');
		echo("Current time on server: ".$now."<br>");
		
		echo('<a href="javascript:unhide(\'UsersList\');">Display list of users / Hide</a><br>');
		echo('<div id="UsersList" class="admHidden">');
		echo('<table style="width:600px">');
				foreach ($users as $auser)
				{
					echo ("<tr><td>".$auser->getid()."</td><td>".$auser->getfirstname()."</td><td>".$auser->getlastname()."</td><td>".$auser->getregistrationdate()."</tr>");
				}
		echo('</table></div>');
		echo('<h3>Email</h3>');
		echo('<table><tr><td><br><form method="post" action="admin_email.php"><input type="hidden" name="action" value="open_email_test" /><input class="adminEmailButton" type="submit" value="Send test email"><br>Send a test email to the guardians.</form></td>');
		echo('<td><br><form method="post" action="admin_email.php"><input type="hidden" name="action" value="open_email_allplayers" /><input class="adminEmailButton" type="submit" value="Send email to all players"><br>Send an email to all players.</form></td>');
		echo('<td><br><form method="post" action="admin_email.php"><input type="hidden" name="action" value="open_email_someplayers" /><input class="adminEmailButton" type="submit" value="Send email to some players"><br>Send an email to some players.</form></td></tr></table>');
				
		$emails = admin_getallemails($bdd);
		echo('<br><a href="javascript:unhide(\'UsersAllEmails\');">Display list of all emails / Hide</a><br>');
		echo('This list is for the guardians only, some users can have requested to not receive email from other players. See list of public emails below.<br>');
		echo('<div id="UsersAllEmails" class="admHidden">');
		echo('<table style="width:800px" class="bluetext"><tr><td>');
				foreach ($emails as $anemail)
				{
					echo ("".$anemail."; ");
				}
		echo('</td></tr></table></div>');
		
		$publicemails = admin_getallpublicemails($bdd);
		echo('<br><a href="javascript:unhide(\'UsersAllPublicEmails\');">Display list of all public emails / Hide</a><br>');
		echo('This list is the list of email of users who accepted to receive emails from other players.<br>');
		echo('<div id="UsersAllPublicEmails" class="admHidden">');
		echo('<table style="width:800px" class="bluetext"><tr><td>');
				foreach ($publicemails as $apublicemail)
				{
					echo ("".$apublicemail."; ");
				}
		echo('</td></tr></table></div>');
	?>
</section>