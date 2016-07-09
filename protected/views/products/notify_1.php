<?php

	$this->breadcrumbs=array(
		'Products'=>array('index'),
		$model->title,
	);
	
	$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->pid)),
	array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
	array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->pid)),
);

?>
<h1> Send Notifications Product <?php echo $model->title ;?></h1>
<input type="hidden" id="ProdID" value="<?php echo $model->pid;?>" />
<input type="hidden" id="CatID" value="<?php echo $model->csid;?>" />
<?php if($type == 0):?>
	
	<p> Send Notification For Product <?php echo $model->title;?> to Customers Subscriped Category <?php echo $model->cs->title;?></p>
	
	<a href="/index.php/products/OpenNotify/<?php echo $model->pid?>?type=1">Next</a>
		
<?php endif;?>

<?php if($type == 1):?>
	
	 <label>Notification Message</label>
	 <br />
	 <textarea id="MessTxt" rows="4" cols="50" maxlength="50">
	 	
	 </textarea> 
	 <br />
	<button id="BtnSubProNo">Submit</button>
	
<?php endif;?>

<?php if($type == 3):?>
	
	<p>
	   You Sended Notification For Product <?php echo $model->title;?> <br />
	   to Customers Subscriped Category <?php echo $model->cs->title;?> Today <br />
	   So You Cann't Send Today Again
	</p>
	
	
		
<?php endif;?>

<script>

	$('#BtnSubProNo').click(function(e){
		
		if($('#MessTxt').val().trim() == ''){
			
			alert('Message is Empty');
			
			$('#MessTxt').focus();
			
		}else{
			
			var DA ={
				
				ProdID : $('#ProdID').val(),
				CatID : $('#CatID').val(),
				MessTxt : $('#MessTxt').val()
			};
			
			$.post('/index.php/products/ajaxSubmitNotify',DA,function(data){
			
				if(data.trim() != ''){
					alert(data);
				}
				
			});
		}	
		
	});
	
</script>



