<?php include 'includes/wrapper/wr_header.php'; ?>
	<h1>Welcome to our Evaluation Page!</h1>
	<hr/>

<?php
if($_SESSION['username']){//if user is logged in	
	$username = $_SESSION['username'];
	$user_query = mysql_query("SELECT  `users`.`user_id` FROM  `users` WHERE (`users`.`username`= '$username')");
	while($user_row = mysql_fetch_assoc($user_query)){
		$user_id=$user_row['user_id'];
	}

	include 'includes/widgets/product_evaluation_form.php';

	if ($_POST)
	{			
			$user=$user_id;
			$product=$_SESSION['product_more_id'];
			$title= $_POST['title']; //'Title of comment';
			$rate= $_POST['rate']; //4;
			$time_of_use= $_POST['time_of_use']; //4;
			$positive= $_POST['positive']; //'positive comment';
			$negative= $_POST['negative']; //'negative comment';
			$other= $_POST['other']; //'other comment';
			$comment_query=mysql_query("INSERT INTO `comments` (`user_id`, `product_id`, `date`, `title`, `rate`, `time_of_use`, `positive`, `negative`, `other`) VALUES ( $user, $product, '2013-02-01', '$title', $rate, $time_of_use, '$positive', '$negative', '$other')");
			echo '<strong>'.$_SESSION['username'].'</strong>, your comment is saved!';	
	}
	
}else{ //if not logged in either register to get an account or log in
	echo '<table width="600"><tr><td align="center">You must be logged in to comment on a product..! Would you like to <a href="register.php"><strong>register</strong></a>?</td></tr></table>';
}
?>
<?php include 'includes/wrapper/wr_footer.php'; ?>
