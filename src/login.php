<?php include 'includes/wrapper/wr_header.php'; ?>
	<br/><h1>Welcome to our Login Page!</h1>
	<p>login</p>

	<table border="0" width="600px" height="409" style="margin-top:10px;" cellpadding="20" background="images/book.png"><tr><td>
		<?php
		//session_start();
		$username = $_POST['username'];
		$password = $_POST['password'];

		//1st check if there is the user in db
		if($username&&$password){
			//$connect = mysql_connect("localhost", "root", "password") or die("Couldn't connect!");
			//mysql_select_db("pspi_eshop") or die("Couldn't find db");

			$query = mysql_query("SELECT * FROM users WHERE username = '$username'");
			$numrows = mysql_num_rows($query);

			if($numrows!=0){ //code to login
				while($row = mysql_fetch_assoc($query)){
					$dbusername = $row['username'];
					$dbpassword = $row['password'];
				}
				//check to see if they match!
				if($username==$dbusername && $password==$dbpassword){
					//echo "You're in! <a href='index.php'>Click</a> here to enter the index page as logged user.";
					$_SESSION['username']=$dbusername;
					header("Location: index.php");
				}else{
					echo "Incorrect password!";
				}
			}else{
				die("That user doesn't exist!");
			}
		}else{
			die("Please enter a username and a password!");
		}

		?>
	</td></tr></table>

<?php include 'includes/wrapper/wr_footer.php'; ?>
