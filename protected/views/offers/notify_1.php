<?php

	$this->breadcrumbs=array(
		'Products'=>array('index'),
		$model->title,
	);
	
	$this->menu=array(
		array('label'=>'List Offers', 'url'=>array('index')),
		array('label'=>'Create Offers', 'url'=>array('create')),
		array('label'=>'Update Offers', 'url'=>array('update', 'id'=>$model->ofid)),
		array('label'=>'View Offers', 'url'=>array('view', 'id'=>$model->ofid)),
		array('label'=>'Manage Offers', 'url'=>array('admin')),
	);

?>
<h1> Send Notifications Offer <?php echo $model->title ;?></h1>
<input type="hidden" id="OffID" value="<?php echo $model->ofid;?>" />
<input type="hidden" id="ProdID" value="<?php echo $model->pid;?>" />
<?php if($type == 0):?>
	
	<p> Send Notification For Offer <?php echo $model->title;?> to Customers </p>
	
	<a href="/index.php/offers/OpenNotify/<?php echo $model->ofid?>?type=1">Next</a>
		
<?php endif;?>

<?php if($type == 1):?>
	
	 <label>Notification Message</label>
	 <br />
	 <textarea id="MessTxt" rows="4" cols="50" maxlength="50">
	 	
	 </textarea> 
	 <br />
	<button id="BtnSubOffNo">Submit</button>
	
<?php endif;?>

<?php if($type == 3):?>
	
	<p>
	   You Sended Notification For Offer <?php echo $model->title;?> <br />
	   to Customers Today <br />
	   So You Cann't Send Today Again
	</p>
		
<?php endif;?>

<script>

	$('#BtnSubOffNo').click(function(e){
		
		if($('#MessTxt').val().trim() == ''){
			
			alert('Message is Empty');
			
			$('#MessTxt').focus();
			
		}else{
			
			var DA = {
				
				ProdID : $('#ProdID').val(),
				OffID : $('#OffID').val(),
				MessTxt : $('#MessTxt').val()
			};
			
			$.post('/index.php/offers/ajaxSubmitNotify',DA,function(data){
			
				if(data.trim() != ''){
					alert(data);
				}
				
			});
		}	
		
	});
	
</script>



