<?php
/* @var $this BillingCycleController */
/* @var $data BillingCycle */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('bcid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->bcid), array('view', 'id'=>$data->bcid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bc_duration')); ?>:</b>
	<?php //echo CHtml::encode($data->bc_duration); 
	
		if($data->bc_type == 0){
			echo CHtml::encode($data->bc_duration . ' Days'); 
		}elseif($data->bc_type == 1){
			echo CHtml::encode($data->bc_duration . ' Month'); 
		}elseif($data->bc_type == 2){
			echo CHtml::encode($data->bc_duration . ' Year'); 
		}
	?>
	<br />

	<!-- <b><?php echo CHtml::encode($data->getAttributeLabel('bc_type')); ?>:</b>
	<?php  echo CHtml::encode($data->bc_type); 	?>
	<br /> -->


</div>