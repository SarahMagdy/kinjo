<?php
   $this->breadcrumbs=array(
	'Business Units'=>array('index'),
	'Contact US',
);

		$MenuArr = array(array('label'=>'List BusinessUnit', 'url'=>array('index')),
					 	 array('label'=>'Manage BusinessUnit', 'url'=>array('admin')),
					 	 array('label'=>'Update BusinessUnit', 'url'=>array('update', 'id'=>$model->buid)),
		);
		
		if(Yii::app()->session['User']['UserType']== 'admin'){
			
			array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')));
		}
		$this->menu = $MenuArr;
?>
<title> Contact US </title>
<br />
<h1>Contact US Store <?php echo $model->buid; ?></h1>
<input type="hidden" id="BU_ID" name="BU_ID" value="<?php echo $model->buid; ?>" />
<label>Type</label>
<select id="ContactType" style = " width: 100px;">
	<option value =""> -- Type -- </option>
	<option value ="1">Phone</option>
	<option value ="2">Adress</option>
	<option value ="3">Fax</option>
	<option value ="4">E-Mail</option>
</select>
<input id="ContactVal" value="" style = " width: 300px;"/>
<button id="btn_add"> Add </button>
<br /><br />
<div id="ContactDiv" class="grid-view">
	<table class="items">
		<thead>
			<th></th>
			<th></th>
			<th></th>
		</thead>
		<tbody>
			<?php if(isset($Contacts)):?>	
				<?php foreach($Contacts AS $key=>$Row):?>
					<tr style="background: none repeat scroll 0 0 #E5F1F4">
						<td><center><?=$Row['bu_contact_title']?></center></td>
						<td><center><?=$Row['bu_contact_val']?></center></td>
						<td style="padding-left: 5%;">
				 	 		<a class="DelContact" id="<?=$Row['bu_contact_id'];?>" title="Delete" href="#"><img src="/assets/8626beb4/gridview/DeleteRed.png" alt="Delete"></a>
				 	 	</td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>		
		</tbody>
	</table>
</div>
<script>
	$('#btn_add').click(function(event) {
            id=document.getElementById('BU_ID').value;
            ContactType=document.getElementById('ContactType').value;
            ContactVal=document.getElementById('ContactVal').value;
            selected=$('#ContactType option:selected').text();
	  
		  var da = {
		  		BU_ID:id,
		  		ContactType:ContactType,
		  		ContactTitle:selected,
		  		ContactVal:ContactVal
		  };
		  $.post('/index.php/BusinessUnit/AjaxContactUS',da,function(data) {
			
//				if(data > 0){
                                  console.log(data);
					
				   location.reload();
					
//				}else{
//					
//					if($('#ContactType').val() == 2){
//						
//						alert('Address Inserted Before , You Can Insert Only One Address ');
//					}
//				}
		});
	  
	});
	
	$('.DelContact').click(function(event) {
	  
		  var da = {ConID:this.id};
		  
		  $.post('/index.php/BusinessUnit/AjaxDelContactUS',da,function(data) {
			
				location.reload();
				 
		  });
	  
	});
</script>


