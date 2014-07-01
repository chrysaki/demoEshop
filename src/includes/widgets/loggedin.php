<?php
	//session_start();
	if($_SESSION['username']){
		echo '<h3>Welcome, '.$_SESSION['username'].' !</h3>';
		echo '<p><a href="logout.php">Log out</a></p>';
	}
?>
