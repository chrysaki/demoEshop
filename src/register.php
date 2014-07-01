<?php include 'includes/wrapper/wr_header.php'; ?>
	<br/><h1>Welcome to our Registration Page!</h1>

	<?php
		include 'includes/widgets/registration_form.php';

	if ($_POST)
	{		
		$user=$_POST['user'];
		$pwd=$_POST['pwd'];
		$name=$_POST['name'];
		$surname=$_POST['surname'];
		$email=$_POST['email'];

		//checking if username already exists
		$query = "SELECT `users`.`username` FROM `users` WHERE `users`.`username` = '$user'";
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0) {
			echo "Username already taken";
			exit();
		} else {
			$query=mysql_query("INSERT INTO `users` (`username`, `password`, `first_name`, `last_name`, `email`) VALUES ('$user' , '$pwd', '$name', '$surname', '$email') ");
			echo "Succesful registration!";	

		}
	}
	?>


<?php include 'includes/wrapper/wr_footer.php'; ?>
