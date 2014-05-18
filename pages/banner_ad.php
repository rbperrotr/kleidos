<br>
<div>
	<?php
		
		$ads = ad_getViewableAds($bdd);
		
		foreach ($ads as $ad)
		{
			echo('<img src="resources/'.$ad->getPicture().'"/>⁪');
		}
	?>
</div>