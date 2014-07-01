<?php
include 'database/connect.php'; //connect to server and db
session_start();

//if we have clicked on add - adding the quantity in - check if there is any product left in store else can't add
if(isset($_GET['add'])){
	$quantity = mysql_query('SELECT `id` , `quantity` FROM `products` WHERE `products`.`id`='.mysql_real_escape_string((int)$_GET['add']));
	while($quantity_row = mysql_fetch_assoc($quantity)){
		if($quantity_row['quantity']!= $_SESSION['cart_'.(int)$_GET['add']]){
			$_SESSION['cart_'.(int)$_GET['add']]+='1'; //$_GET['add'] is the id number that we ve passed through our $_GET variable		
		}	
	}
	header('Location: cart.php');
}

//removing an item
if(isset($_GET['remove'])){
	$_SESSION['cart_'.(int)$_GET['remove']]--;
	header('Location: cart.php');
}

//deleting an item
if(isset($_GET['delete'])){
	$_SESSION['cart_'.(int)$_GET['delete']]='0';
	header('Location: cart.php');
}

//more..
if(isset($_GET['more'])){
	$_SESSION['product_more_id']=(int)$_GET['more'];
	header('Location: product_display.php');
}

/////////////////////////////////////////////////PRODUCT FUNCTIONS/////////////////////////////////////////////////
function products($category){ //$category is the id of the category and if 0 is index
	//pagination
	if($category==0){//if index.php we want to display only the new products and only 3 per page
		$per_page = 3;
		$query = "SELECT COUNT(`products`.`id`) 
			FROM `products` WHERE `products`.`new_product`=1";//returning number of records we have in our db
	}else{//else only the category's products and 4 products per page
		$per_page = 4;
		$query = "SELECT COUNT(`products`.`id`) 
			FROM `products` WHERE `products`.`category`=$category";//returning number of records we have in our db
	}	
				
	$pages_query = mysql_query($query);
				
	$pages = ceil(mysql_result($pages_query , 0) / $per_page); //ceil function rounds up ex. 3.3333 = 4 //how many pages we need to split and display

	$page = (isset($_GET['page']) && $_GET['page']>0) ? (int)$_GET['page']: 1;
	$start = ($page - 1) * $per_page; //where we are starting from in our records set in db
	
	$query ="SELECT `products`.* FROM `products` LIMIT $start, $per_page";


 echo '<hr/>';
    //display the pages' links
    if($pages >= 1 && $page<=$pages){//if only one page we dont need to display links to go to links 2 and 3
		for($x = 1; $x <= $pages; $x++){
			echo ($x == $page) ? '<strong><a href="?page='.$x.'">'.$x.'</a></strong> ' : '<a href="?page='.$x.'">'.$x.'</a> ';
		}
    }
	echo '<hr/>';
	
	
	if($category==0){//if zero then index.php and should display only new products
		//query selecting products with new_product = 1
		$query = "SELECT  * FROM  `products` 
			WHERE (`products`.`new_product` =1) 
			ORDER BY `products`.`id` DESC
			LIMIT $start, $per_page";
	}else{ //else display the products of category with id $category
		$query = "SELECT * FROM `categories`
				LEFT JOIN `pspi_eshop`.`products` ON `categories`.`id` = `products`.`category` 
				WHERE ((`products`.`quantity` >0) AND (`categories`.`id` =$category)) 
				ORDER BY `products`.`id` DESC
				LIMIT $start, $per_page";
	}
	
	$get_query = mysql_query($query);
	if(mysql_num_rows($get_query)==0){
		echo "There are no products to display!";
	}
	else{
		if($category==0){
			echo  '<p><img src="images/label_new.png" alt="newp" width="30" height="30" align="center" hspace="0">Check our newest products below!</p>';
		}
		
		while($get_row = mysql_fetch_assoc($get_query)){
		echo '
			<table width="600px" style="margin-top:10px;" cellpadding="">
			<tr>
			<td width="40%" ><h4>'; 
			if($get_row['new_product']==1){
				echo '<img src="images/label_new.png" alt="newp" width="30" height="30" align="center" hspace="10">';
			}
		echo 'Name =  '.$get_row['name'].'</h4>
			<p><img src="'.$get_row['image'].'" alt="image" width="100" height="100"></p>
			</td>
			<td align="center" valign="top">
			<p>Description = '.$get_row['description'].'</p>
			<p>Price = &euro; '.$get_row['price'].'</p>
			<p>Shipping = &euro; '.$get_row['shipping'].'</p>
			<p><a href="cart.php?add='.$get_row['id'].'">Add</a></p><hr/>
			<p><a href="cart.php?more='.$get_row['id'].'">more</a></p>
			</td>
			</tr>
			</table> <br/>	
		';
		}
	}
	
  //display the pages' links
    if($pages >= 1 && $page<=$pages){//if only one page we dont need to display links to go to links 2 and 3
		echo '<hr/>';
		for($x = 1; $x <= $pages; $x++){
			echo ($x == $page) ? '<strong><a href="?page='.$x.'">'.$x.'</a></strong> ' : '<a href="?page='.$x.'">'.$x.'</a> ';
		}
    }
}

//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
function filters($category){
	$c=$_GET['char'];
		
	$query = "SELECT  `characteristics`.`name` FROM  `characteristics` WHERE (`characteristics`.`category` =$category)";
	$que = mysql_query($query);
	
	while($que_row = mysql_fetch_assoc($que)){
		if(isset($_GET[$que_row['name']])){
			$nametable[$que_row['name']] = $_GET[$que_row['name']];
		}
	}
	
	$i=0;
	$idArr=array();
	$nameArr=array();
	$characteristics_values=array();
	if(!isset($nametable)){
		$query = "SELECT  `characteristics`.`id`,`characteristics`.`name`  
					FROM  `characteristics`  
					WHERE (	`characteristics`.`category` =  $category)";
	}else{
		$str = "";
		$count =0;
		foreach($nametable as $key => $value){
			if ($count!=0){
				$str = $str.',';
			}
			$str = $str ."'$key'";
			$count++;			
		}
		echo 'chosen filters : &nbsp;&nbsp;'.$str;
		$query = "SELECT  `characteristics`.`id`,`characteristics`.`name`  
				FROM  `characteristics`  
				WHERE (	`characteristics`.`category` =  $category AND `characteristics`.`name` 	NOT IN ($str))";
	}
	
	$que = mysql_query($query);
	$i=0;		
	while($que_row = mysql_fetch_assoc($que)){
		$idArr[$i]=$que_row['id'];
		$nameArr[$i]=$que_row['name'];
		$i++;
	}	
			
	//display table of filters
		echo '<table width="600px" style="margin-top:10px;"  cellpadding="10" background="images/page.png">';
			for($x=0;$x<$i;$x++){					
				echo '<tr><th width="30%" align="right"> '.$nameArr[$x].' : </th>
					<td align="left">';
				$query2 = "SELECT DISTINCT `product_characteristic`.`value`, `characteristics`.`name` 
						FROM `characteristics` LEFT JOIN `pspi_eshop`.`product_characteristic` 
						ON `characteristics`.`id` = `product_characteristic`.`characteristic_id` 
						WHERE (`characteristics`.`id` =$idArr[$x])";
				$que = mysql_query($query2);
				while($que_row = mysql_fetch_assoc($que)){
					$url = "?";
					foreach($nametable as $key=>$value){
						$url= $url.$key.'='.$value.'&';
					}
					$url=$url.$que_row['name'].'='.$que_row['value'];
					echo ' &nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$url.'">'.$que_row['value'].'</a>&nbsp;&nbsp; ';
				}	
				echo '</td></tr>';		
			}
		echo '<tr><td colspan="2" align="center"><strong><a href="category'.$category.'.php">All products</a></strong></td></tr></table>';

	//query επιλογής φίλτρων. Αν ένα φίλτρο είαι επιλεγμένο και επιλεγεί κιάλλο εμφάνιση μόνο αντίστοιων προϊόντων 
	if(isset($nametable)){
		$query = "";
		$count =0;
		foreach($nametable as $key => $value){
			if ($count == 0){
				$query = "SELECT product_characteristic.product_id\n"
				. "	 FROM product_characteristic \n"
				. "	 INNER JOIN characteristics ON product_characteristic.characteristic_id = characteristics.id\n"
				. "	 WHERE ((characteristics.name LIKE '$key') \n"
				. "	 AND (product_characteristic.value LIKE '$value'))";	
			}else{
				$query = "SELECT product_characteristic.product_id\n"
				. "	 FROM product_characteristic \n"
				. "	 INNER JOIN characteristics ON product_characteristic.characteristic_id = characteristics.id\n"
				. "	 WHERE ((characteristics.name LIKE '$key') \n"
				. "	 AND (product_characteristic.value LIKE '$value')) \n"
				. "  AND (product_characteristic.product_id IN ($query))";
			}
			$count++;	
		}
		//εμφάνιση προϊόντων που διαθέτουν τα χαρακτηριστικά που επιλέχθηκαν από τα φίλτρα
		$query ="SELECT  `products` . * 
			FROM  `products` 
			WHERE (`products`.`id` IN ($query))";
		//echo $query;
		$que = mysql_query($query);
		
		//if 0 then mysql_query returned 0 lines so we dont have any products with this combination of characteristics
		if(mysql_num_rows($que)==0){
			echo "<br/><h3>There are no products to display!</h3>";
		}
		
		while($get_row = mysql_fetch_assoc($que)){
			//echo $get_row['name'].'<br/>';
			echo '
			<table width="600px" style="margin-top:10px;" bgcolor="#EEEEEE" cellpadding="10">
			<tr>
			<td width="40%" ';
			if ($get_row['new_product']==1) echo 'background="images/label_new.png"';
			echo '>
			<h4>Name =  '.$get_row['name'].'</h4> 
			<img src="'.$get_row['image'].'" alt="image" width="100" height="100">
			</td>
			<td align="center" valign="top">
			<p>Description = '.$get_row['description'].'</p>
			<p>Price = &euro; '.$get_row['price'].'</p>
			<p>Shipping = &euro; '.$get_row['shipping'].'</p>
			<p><a href="cart.php?add='.$get_row['id'].'">Add</a></p><hr/>
			<p><a href="cart.php?more='.$get_row['id'].'">more</a></p>
			</td>
			</tr>
			</table> <br/>	
			';	
		}	
	}else{
		products($category);
	}		
		
}//end of filters function

//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////CART FUNCTIONS//////////////////////////////////////////////////
//this function is gonna list all items in cart
function paypal_items(){
	$num = 0;
	foreach($_SESSION as $name => $value){
		if($value!=0){
			if(substr($name, 0, 5)=='cart_'){
				$id = substr($name, 5, strlen($name)-5);
				$get = mysql_query('SELECT id, name, price, shipping FROM products WHERE id = '.mysql_real_escape_string((int)$id));
				while($get_row = mysql_fetch_assoc($get)){
					$num++;
					echo '<input type="hidden" name="item_number_'.$num.'" value="'.$id.'">';
					echo '<input type="hidden" name="item_name_'.$num.'" value="'.$get_row['name'].'">';
					echo '<input type="hidden" name="amount_'.$num.'" value="'.$get_row['price'].'">';
					echo '<input type="hidden" name="shipping_'.$num.'" value="'.$get_row['shipping'].'">';
					echo '<input type="hidden" name="shipping2_'.$num.'" value="'.$get_row['shipping'].'">';
					echo '<input type="hidden" name="quantity_'.$num.'" value="'.$value.'">';
				}
			}
		}
	}
}

function cart($simple){ //$simple = we want a simple cart display for the leftbar or a full cart display on the cart.php
	foreach($_SESSION as $name => $value){ //cart_1 = 3
		if($value>0){ //cause we dont wanna display anything if value is not greater than 0
			if(substr($name, 0, 5)=='cart_'){
				$id = substr($name, 5, strlen($name)-5); //cart_12 then id=12
				$get = mysql_query("SELECT `id`, `name`, `price`, `image` FROM `products` WHERE `id` = ".mysql_real_escape_string((int)$id));
				while($get_row = mysql_fetch_assoc($get)){
					$sub = $get_row['price']*$value;
					$num_products += $value;
					if($simple!='simple'){
						echo '
							<table width="600px" style="margin-top:10px;" cellpadding="0">
							<tr>
							<td width="40%">
							<p><img src="'.$get_row['image'].'" alt="image" width="100" height="100"></p></td>
							<td>
							<p>'.$get_row['name'].' x Quantity = '.$value.' @ &euro;'.number_format($get_row['price'], 2).' = &euro;'.number_format($sub, 2).'</p>
							<p><a href="cart.php?remove='.$id.'">[-]</a> <a href="cart.php?add='.$id.'">[+]</a> <a href="cart.php?delete='.$id.'">[Delete]</a></p>
							</td>
							</tr>
							</table>
						';
					}
				}
			}
			$total += $sub;
		}
	}
	echo '<br/>';
	echo '
			<table width="165px" style="margin-top:10px;">
			<tr>
			<td align="center">
			<p><img src="images/cart.jpg" alt="cart" width="80" height="80"></p>
		';
		if($total == 0){
			echo "Your cart is empty.";
		}else{
			echo '<p>No of Products = '.$num_products.'</p><p>Total : &euro;'.number_format($total, 2).'</p>';		
		}
	echo '
			</td>
			</tr>
			';if($simple=='simple'){echo '
				<tr>
				<td align="center" valign="top">
				<a href="cart.php">YOUR CART</a>
				</td>
				</tr>
			';}echo '
			</table> 
		';
	
	if($total == 0 && $simple!='simple'){
		echo "<h2>Your cart is empty.</h2>";
	}else{
		if($simple!='simple'){
		?>
		<p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="business" value="chkirmit@csd.auth.gr">
		<?php paypal_items(); ?> <!--this function is gonna list all items in cart-->
		<input type="hidden" name="currency_code" value="EUR">
		<input type="hidden" name="amount" value="<?php echo $total; ?>">
		<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but03.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
		</form>
		</p>
		<?php
		}
	}
}//end of function

?>
