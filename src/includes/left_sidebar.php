<left_sidebar>
	<?php
		if($_SESSION['username']){
			include 'widgets/loggedin.php';
		}else{
			include 'widgets/login_form.php';
		}
	?>
		<?php include 'widgets/simple_display.php'; ?>
</left_sidebar>
