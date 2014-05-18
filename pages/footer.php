<br>
<footer>
	<table>
		<tr>
			<td style="width:50%;text-align:left;vertical-align:top;"><a href="mailto:guardians@kleidos.tk">Contact the guardians</a>.</td>
			<?php
				$ads = ad_getViewableAds($bdd);		
				if(count($ads)>0)
				{	
					echo('<td>');
					foreach ($ads as $ad)
					{
						echo('<a href="'.$ad->getURL().'" target="_blank"><img src="resources/'.$ad->getPicture().'"/></a>');
					}
					echo('</td>');
				}
			?>
			
		</tr>
	</table>
	
</footer>