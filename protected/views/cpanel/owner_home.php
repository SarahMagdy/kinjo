<?php 
	
	$this->breadcrumbs=array(
		//'Owners'=>array('index'),
		// $model->title=>array('view','id'=>$model->pid),
		'Owner Home',
	);
	
/*	$this->menu=array(
	
		array('label'=>'My C-Panel', 'url'=>array('cpanel/'.Yii::app()->session['User']['UserID'])),
		array('label'=>'Stores C-Panel', 'url'=>array('cpanel/admin')),
		array('label'=>'Stores', 'url'=>array('BusinessUnit/admin')),
		//array('label'=>'Orders', 'url'=>array('OrdersDetails/customGrid'))
	);
	*/
?>


<div id="StoresDiv">
	
	<?php foreach($Stores AS $Key=>$Row):?>
		
		<div id="div_<?=$Row['buid'];?>" class="S_div" style="float: left;margin: 2%;">
			
			 <img src="<?= Yii::app()->request->baseUrl.'/images/upload/business_unit/'.$Row['logo']?>" alt="<?=$Row['title'];?>" height="150" width="150"> 
			 </br>
			 <a id="link_<?=$Row['buid'];?>" class="S_link" href="#" SID="<?=$Row['buid'];?>"><B><?=$Row['title'];?></B></a> 
		</div>
		
	<?php endforeach;?>
	
</div>

<script>
	
	$('.S_link').click(function(e){
		
		
		 var store_id = {store_id:$(this).attr('SID')};
		  $.post('/index.php/cpanel/AjaxHome/',store_id,function(data){
			 location.reload();
		  });
		
		
		
		
		/*
		var order = {customer:'{"cust_id":"1","fname":"Asmaa","lname":"Ali", "pass":"123", "gender":"1","b_date":"1990-1-20","coun_id":"1"}'};
				$.post('/index.php/API/UpdateCustomer',order,function(data){		
					alert(data);
				});*/
		
		//$.get('/index.php/API/ImageCustomer?CustID=1&&imgname=1424081211-0-product-hugerect-108166-42605-1367940331-414db1dd6a4e77678635ca95091ff99a.jpg&&');
		
		
	});
</script>