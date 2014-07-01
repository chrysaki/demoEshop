<?php 
include 'includes/wrapper/wr_header.php'; ?>

<?php
if (isset($_REQUEST['email']))
//Αν το "email" εχει συμπληρωθεί τοτε γίνεται αποστολή
{
	//αποστολή email
	$email = $_REQUEST['email'] ;
	$subject = $_REQUEST['subject'] ;
	$message = $_REQUEST['message'] ;
	mail( "chkirmit@csd.auth.gr", "Subject: $subject",$message, "From: $email" );
	echo "Thank you for using our mail form";
}
else
//Αν το "email" ∆ΕΝ εχει συμπληρωθεί ξαναδημιουργείται η φόρμα
{
?>
	<br/><h1>Welcome to our Contact page!</h1>
	<p>send us your message!</p>
	<hr/>
	<table width="600px" height="380px" style="margin-top:10px;" background="images/book.png" cellspacing="20"><tr><td align="center">
	<?php
		echo "<form method='post' action='contact.php'>
		Email: <input name='email' type='text' /><br /><br />
		Subject: <input name='subject' type='text' /><br /><br />
		Message:<br />
		<textarea name='message' rows='10' cols='35'>
		</textarea><br /><br />
		<input type='submit' />
		</form>";
	}?>
	</td></tr></table>
<?php include 'includes/wrapper/wr_footer.php'; ?>
