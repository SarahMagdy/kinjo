<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title=>array('view','id'=>$model->pid),
	'Colors Setting',
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->pid)),
	array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->pid)),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);
?>

<h1>Products Colors Setting  #<?php echo $model->pid; ?></h1>
</br>

	<input hidden type="text" name="proID" value="<?php echo $model->pid;?>" id="proID"/>

	<div class="row">
		<label><b>Color Name</b></label></br>
		<input id="color_name" name="color_name" type="text" value="" />
	</div>
	
	</br>
	
	<div class="row">
		<label><b>Choose Color</b></label></br>
		<?php $this->widget('application.extensions.colorpicker.EColorPicker', 
	              array(
	                    'name'=>'color_code',
	                    'mode'=>'textfield',
	                    'fade' => false,
	                    'slide' => false,
	                    'curtain' => true,
	                    'value'=>'',
	                    'id'=>'color_code'
                   )
             );?>
		<button id="submitColor" name="submitColor" style="height:26px;background-color: #E5F1F4;">Add Color</button>
	
	</div>
	
	
	
	</br>
	<div id="prodColorsGrid" class="grid-view">
		<table class="items">
			<thead>
				<tr>
					<th style="width: 10%;"></th>
					<th>ID</th>
					<th>Color Name</th>
					<th>Color Code</th>
					<th></th>
					<th>Languages</th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach($ProColors_arr AS $key=>$row):?>
					<tr style="background: none repeat scroll 0 0 #E5F1F4">
						<td style="padding-left: 5%;">
				 	 		<a class="del_color" id="<?=$row['color_id'];?>" title="Delete" href="#"><img src="/assets/8626beb4/gridview/DeleteRed.png" alt="Delete"></a>
				 	 	</td>
						<td class="ID_C"><?php echo $row['color_id']?></td>
						<td><?php echo $row['color_name']?></td>
						<td><?php echo $row['color_code']?></td>
						<td >
							<div style="padding:10px 3px 10px 3px;background-color: #<?php echo $row['color_code'];?> ;">
							</div>
							
						</td>
						<td class="LangADD <?php echo $row['color_id']?>"></td>
					</tr>
				<?php endforeach;?>
			</tbody>
			
		</table>
	</div>
	
	



<script>

	$(document).ready(function(){
		ReloadJs();
	});
	function ReloadJs(){
		var contname = "'colors'";
		$.post('/index.php/Common/GetLang',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td.ID_C').text();
					for (var key in end_data){
						if($('.LangADD.'+RowID).length){
							$('.LangADD.'+RowID).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key]['LangID']+','+RowID+')" class ="LangOpen" contname="colors" langid="'+end_data[key]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key]['LangC']+'.png"  width="15" height="15"/></a>');	
						}	
					}
				});
			}
		});
	}
	
	$('#color_code').attr('style', 'width:230px;');
	
	$('#submitColor').click(function(e){
		var data ={
			color_code : $('#color_code').val(),
			color_name : $('#color_name').val(),
			proID : $('#proID').val()
		}; 
		
		$.post( "/index.php/products/Color/"+$('#proID').val(),data, function( data ) {
			location.reload();
		});
	});
	
	
	$('.del_color').click(function(e){
		
		var data ={
			colorId : this.id
		}; 
		
		$.post( "/index.php/products/ajaxDeleteColor/",data, function( data ) {
			location.reload();
		});
	});
</script>







