<?php
	class Ad
	{
		private $id;
		private $title;
		private $picture;
		private $url;
		private $startDate;
		private $endDate;
		
		function Ad($n_id, $n_title, $n_picture, $n_url, $n_startDate, $n_endDate)
		{
			$this->id = $n_id;
			$this->title = $n_title;
			$this->picture = $n_picture;
			$this->url = $n_url;
			$this->startDate = $n_startDate;
			$this->endDate = $n_endDate;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setId($newID)
		{
			$this->id = $newID;
		}
		
		public function getTitle()
		{
			return $this->title;
		}
		
		public function setTitle($newTitle)
		{
			$this->title = $newTitle;
		}
		
		public function getPicture()
		{
			return $this->picture;
		}
		
		public function setPicture($newPicture)
		{
			$this->picture = $newPicture;
		}
			
		public function getURL()
		{
			return $this->url;
		}
		
		public function setURL($newURL)
		{
			$this->url = $newURL;
		}
		
		public function getStartDate()
		{
			return $this->startDate;
		}
		
		public function setStartDate($newStartDate)
		{
			$this->startDate = $newStartDate;
		}
		
		public function getEndDate()
		{
			return $this->endDate;
		}
		
		public function setEndDate($newEndDate)
		{
			$this->endDate = $newEndDate;
		}
		
	}
?>