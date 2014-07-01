<div class="widget">
	<h2>Evaluation Form</h2>
	<div class="inner">
	<form action='evaluation.php' method='POST'>
		<p>Title : <input type="text" name="title"></p>
		<p>Rate : <input type="radio" name="rate" value="1">1
		<input type="radio" name="rate" value="2">2
		<input type="radio" name="rate" value="3">3
		<input type="radio" name="rate" value="4">4
		<input type="radio" name="rate" value="5">5</p>
		<p>time of use : <input type="radio" name="time_of_use" value="1">1-7 days
		<input type="radio" name="time_of_use" value="2">1-4 weeks
		<input type="radio" name="time_of_use" value="3">1-12 months
		<input type="radio" name="time_of_use" value="4">> year</p>
		<p><label for="positive">Yper :</label><br/><textarea name="positive" id="positive" rows="6" cols="30"></textarea></p>
		<p><label for="negative">Kata : </label><br/><textarea name="negative" id="negative" rows="6" cols="30"></textarea></p>
		<p><label for="other">Alla : </label><br/><textarea name="other" id="other" rows="6" cols="30"></textarea></p>
		<p><input type="submit" value="submit" /></p>
		</form>	
	</div>
</div>
