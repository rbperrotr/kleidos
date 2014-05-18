<?php
	
	//Get ad according to today
	
	function enigma_getAllAds($bdd)
	{
		$query = $bdd->prepare('SELECT * FROM ad');
		$query->execute(array(
		));
		
		try
		{
			$nb_rows = $query->rowCount();
		}
		catch(Exception $e)
		{
			$nb_rows = 0;
		}
		echo_debug("ad_getAllAds | nbrows =".$nb_rows."<br/>");
		$ads = array();
		if($nb_rows > 0)
		{
			while ($data = $query->fetch())
			{
				$ad = new Ad($data['id'], $data['title'], $data['picture'], $data['url'], $data['startDate'], $data['endDate']);
				$ads[] = $ad;
			}
		}
		return $ads;
	}
	
	//get only viewable ads
	function ad_getViewableAds($bdd)
	{
		$ads = enigma_getAllAds($bdd);
		
		$today = date('Y-m-d H:i:s');	
		$today = new DateTime($today);
		$today = $today->format('Ymd His');
		
		$viewableAds = array();
		foreach($ads as $ad)
		{
			$startDate = (string)$ad->getStartDate();
			$startDate = new DateTime($startDate);
			$startDate = $startDate->format('Ymd His');
			$endDate = (string)$ad->getEndDate();
			$endDate = new DateTime($endDate);
			$endDate = $endDate->format('Ymd His');
			
			if($today >= $startDate && $today <= $endDate )
			{
				$viewableAds[] = $ad;
			}
		}

		return $viewableAds;
	}
	
	
	//get ad by title
	function ad_getAd($bdd, $title)
	{
		$response = $bdd->prepare('SELECT * FROM ad WHERE title = :title');
		$response->execute(array(
				'title' => $title
		));
		while ($data = $response->fetch())
		{
			$ad = new Ad($data['id'], $data['title'], $data['picture'], $data['url'], $data['startDate'], $data['endDate']);
		}
		return $ad;
	}
?>