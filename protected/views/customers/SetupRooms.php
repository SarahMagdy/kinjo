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
h3{
    color: blue;
    margin-left: 38px;
}
</style>
<div>
	<h1>setup rooms</h1>
</div>
<form type="submit" method="post" action="/index.php/Customers/SetupRooms" id="form">
	<div class="division-part">
            <div><h3>garden view</h3>			
                   <img src="http://kinjo.local/images/1garden.png">
		</div>
		<div>
			<input name="room1type" value="<?=$Data['typeGarden'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
            <div><h3>street view</h3>
			
                        <img src="http://kinjo.local/images/2street.png">
                     
		</div>
		<div>
			<input name="room2type" value="<?=$Data['typeStreet'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	<div class="division-part">
            <div><h3>ocean view</h3>
                    <img src="http://kinjo.local/images/3ocean.png">
			
		</div>
		<div>
			<input name="room3type" value="<?=$Data['typeOcean'];?>" class="input-count" type="text" placeholder="count">
		</div>
	</div>
	
	
	<div class="clear-10-top"></div>
	<div class="input-count">
		<input class="action-button shadow animate blue" type="submit" value="SetupRooms">
	</div>
	<div class="clear-10-top"></div>
</form>
