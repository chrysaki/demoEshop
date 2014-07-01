<?php
$category_query = mysql_query('SELECT * FROM  `categories`');
while($category_row = mysql_fetch_assoc($category_query)){
	echo '
		<table border="0" width="600px" height="380px" style="margin-top:10px;" cellspacing="30" cellpadding="10" background="images/book.png"><tr><td align="center">
		<tr>
		<td align="center" valign="top">
		<h2>'.$category_row['name'].'</h2>
		<p>'.$category_row['description'].'</p>
		<p><a href="category'.$category_row['id'].'.php">Go</a></p></td></tr></table>
	';
}
?>
