<style type="text/css">
.division-part{
	width:28%;
	float: left;
	border:1px solid #eaeaea;
	border-radius: 5px;
	padding:5px;
	margin: 0 15px 10px 0 ;
}
.input-count{
	width:99%;
	height:30px;
}
.clear-10-top{
	width:100%;
	clear:both;
	height:10px;
}
</style>
<div>
	<h1>Assign your tables</h1>
</div>
<form type="submit" method="post" action="/index.php/BusinessUnit/AssignTables" id="AssignTables-form" >
	<div class="division-part">
		<div>
			<img src="http://net-charizma.com/images/table2chairs.png">
		</div>
		<div>
			<input name="table2chairs" value="<?=$Data['T2chairs'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
		<div>
			<img src="http://net-charizma.com/images/table4chairs.png">
		</div>
		<div>
			<input name="table4chairs" value="<?=$Data['T4chairs'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
		<div>
			<img src="http://net-charizma.com/images/table6chairs.png">
		</div>
		<div>
			<input name="table6chairs" value="<?=$Data['T6chairs'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
		<div>
			<img src="http://net-charizma.com/images/table8chairs.png">
		</div>
		<div>
			<input name="table8chairs" value="<?=$Data['T8chairs'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
		<div>
			<img src="http://net-charizma.com/images/table10chairs.png">
		</div>
		<div>
			<input name="table10chairs" value="<?=$Data['T10chairs'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="clear-10-top"></div>
	<div class="input-count">
		<input class="action-button shadow animate blue" type="submit" value="Finalize">
	</div>
	<div class="clear-10-top"></div>
</form>