<?php 
	
	function includeBanner()
	{
		if(isset($_SESSION['user']))
		{
			include('pages/banner_logged.php');
		}
		else
		{
			include('pages/banner_notlogged.php');
		}
	}
	
	function includeBannerCreateUser()
	{
		include('pages/banner_createuser.php');
	}
	
	function includeFooter()
	{
		include('pages/footer.php');
	}
	
	function includeIndexContent()
	{
		if(isset($_SESSION['user']))
		{
			include('pages/index_content_logged.php');
		}
		else
		{
			include('pages/index_content_notlogged.php');
		}
	}
	
	function includeBuyClue()
	{
		include('pages/enigma_buyclue.php');
	}
	
	function checkLogin()
	{
		if(isset($_SESSION['user']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkEmail()
	{
		
	}
	
	function echo_debug($comment)
	{
		if(isset($_SESSION['DEBUG']))
		{
			if($_SESSION['DEBUG']==1)
			{
				echo $comment;
			}
		}
	}
	
	function canemail()
	{
		if(isset($_SESSION['EMAILNOTIF']))
		{
			if($_SESSION['EMAILNOTIF']==1)
			{
				$canemail=  TRUE;
				echo_debug("CAN EMAIL | Send email notif<br>");
			}
			else
			{
				$canemail=FALSE;
				echo_debug("CAN EMAIL | Do not send email notif<br>");
			}
		}
		else
		{
			$canemail=FALSE;
		}
		return $canemail;
	}
	
	function notifyOnEachAnswer()
	{
		if(isset($_SESSION['ANSWERS_NOTIF']))
		{
			if($_SESSION['ANSWERS_NOTIF']==1)
			{
				$notifyOnEachAnswer=TRUE;
				echo_debug("NOTIFY ON EACH ANSWER | Notification on each answer is ON<br>");
			}
			else
			{
				$notifyOnEachAnswer=FALSE;
				echo_debug("NOTIFY ON EACH ANSWER | Notification on each answer is OFF<br>");
			}
		}
		else
		{
			$notifyOnEachAnswer=  TRUE;
		}
		return $notifyOnEachAnswer;
	}
?>