<?php include 'includes/wrapper/wr_header.php'; ?>	

	<h1>Welcome to our Product Display Page!</h1>
	<p>just another page</p><hr/>
	
<?php
$id = $_SESSION['product_more_id'];

product_display($id);
echo '<hr/><br/>';
//if logged in user can comment,rate etc..
if($_SESSION['username']){
	echo '<table width="600"><tr><td align="center"><h3>Welcome, '.$_SESSION['username'].' !</h3><br/>Click <a href="evaluation.php">here</a> to evaluate this product</td></tr></table>';
}else{
	echo '<table width="600"><tr><td align="center"><a href="login.php">Log</a> in or <a href="register.php">Register</a> inorder to comment on a Product</td></tr></table>';
}

comment($id);


//go back to previous page
echo '<hr/><br/>';
echo "<br/><a href=\"javascript:history.go(-1)\">GO BACK</a>";


include 'includes/wrapper/wr_footer.php'; ?>


<?php
function product_display($id){
	$product_query = mysql_query('SELECT * FROM `products` WHERE `products`.`id`='.$id);
	while($product_row = mysql_fetch_assoc($product_query)){
		echo '
		<table width="600px" style="margin-top:10px;" bgcolor="#EEEEEE" cellspacing="15" background="images/category.png">
		<tr>
		<td width="50%">
		<p><img src="'.$product_row['image'].'" alt="image" width="250" height="300"></p>
		</td>
		<td align="center" valign="top">
		<h3>Name =  '.$product_row['name'].'</h3>
		<h4>Category = '.$product_row['category'].'</h4>
		<p>Description = '.$product_row['description'].'</p>
		<p>Price = &euro; '.$product_row['price'].'</p>
		<p>Shipping = &euro; '.$product_row['shipping'].'</p>
		<p><a href="cart.php?add='.$product_row['id'].'">Add</a></p>
		</td>
		</tr>
		<tr>
		<td colspan = 2 align="center" valign="top">
		<p>More Info = </p> '.$product_row['More Info'].'
		</td>
		</tr>
		</table>	
		';
	}
}

function comment($id){
	//pagination
	$per_page = 1;//limit of data shown per page
	$query = "SELECT COUNT(`comments`.`id`) FROM `comments` WHERE `comments`.`product_id`=$id";
	$pages_query = mysql_query($query);
	$pages = ceil(mysql_result($pages_query , 0) / $per_page); //ceil function rounds up ex. 3.3333 = 4 //how many pages we need to split and display
	$page = (isset($_GET['page']) && $_GET['page']>0) ? (int)$_GET['page']: 1;
	$start = ($page - 1) * $per_page; //where we are starting from in our records set in db
	
	echo '<hr/>';
    //display the pages' links
    if($pages >= 1 && $page<=$pages){//if only one page we dont need to display links to go to links 2 and 3
		for($x = 1; $x <= $pages; $x++){
			echo ($x == $page) ? '<strong><a href="?page='.$x.'">'.$x.'</a></strong> ' : '<a href="?page='.$x.'">'.$x.'</a> ';
		}
    }
	//echo '<hr/>';

	
	$comment_query = mysql_query("SELECT  `comments` . * ,  `users`.`username` 
						FROM  `users` 
						LEFT JOIN  `pspi_eshop`.`comments` ON  `users`.`user_id` =  `comments`.`user_id` 
						WHERE ((`comments`.`product_id` =$id	)
							AND (`users`.`user_id` =  `comments`.`user_id`))
						LIMIT $start , $per_page"); 

	echo '<table  width="600px" style="margin-top:10px;" cellpadding="0" "><tr><td align="center">';
	if(mysql_num_rows($comment_query)==0){
			echo 'There are no comments to display for this product!';
	}
	while($comment_row = mysql_fetch_assoc($comment_query)){
		echo '<table width="450px" height="320px" style="margin-top:10px;" cellpadding="10" background="images/comment_book.png"><tr><td align="center">';
			echo '<tr><td align="center"><strong>Title</strong> : '.$comment_row['title'].'<hr/></td></tr>';
			echo '<tr><td align="center">Rate : ';
			for($x=1;$x<=5;$x++){
				if($x==$comment_row['rate']){
					echo '<strong>'.$x.'</strong> ';
				}else{
					echo $x.' ';
				}
			}
			echo ' / Time of use : ';
				if($comment_row['time_of_use']==1){
					echo '1-7 days';
				}else if($comment_row['time_of_use']==2){
					echo '1-4 weeks';
				}else if($comment_row['time_of_use']==3){
					echo '1-12 months';
				}else{
					echo '> year';
				}
			echo '<hr/></td></tr>';
			echo '<tr><td valign="top"><strong>Positives</strong> : '.$comment_row['positive'].'</td></tr>';
			echo '<tr><td valign="top"><strong>Negatives</strong> : '.$comment_row['negative'].'</td></tr>';
			echo '<tr><td valign="top"><strong>Others</strong> : '.$comment_row['other'].'</td></tr>';
			echo '<tr><td align="right"><strong>'.$comment_row['username'].'</strong> , ';
			echo $comment_row['date'];'</td></tr>';
		echo '</td></tr></table>';
	}
	echo '</td></tr></table>';
	
	//display the pages' links
    if($pages >= 1 && $page<=$pages){//if only one page we dont need to display links to go to links 2 and 3
		echo '<hr/>';
		for($x = 1; $x <= $pages; $x++){
			echo ($x == $page) ? '<strong><a href="?page='.$x.'">'.$x.'</a></strong> ' : '<a href="?page='.$x.'">'.$x.'</a> ';
		}
    }
	
}
?>
