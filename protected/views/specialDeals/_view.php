<?php
/* @var $this SpecialDealsController */
/* @var $data SpecialDeals */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->sp_d_id), array('view', 'id'=>$data->sp_d_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_bill_cycle_id')); ?>:</b>
	<?php // echo CHtml::encode($data->spDBillCycle->sp_d_bill_cycle_id); 
		if($data->spDBillCycle->bc_type == 0){
			echo CHtml::encode($data->spDBillCycle->bc_duration . ' Days'); 
		}elseif($data->spDBillCycle->bc_type == 1){
			echo CHtml::encode($data->spDBillCycle->bc_duration . ' Month'); 
		}elseif($data->spDBillCycle->bc_type == 2){
			echo CHtml::encode($data->spDBillCycle->bc_duration . ' Year'); 
		}
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_title')); ?>:</b>
	<?php echo CHtml::encode($data->sp_d_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_amount')); ?>:</b>
	<?php echo CHtml::encode($data->sp_d_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_currency')); ?>:</b>
	<?php echo CHtml::encode($data->sp_d_currency); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sp_d_description')); ?>:</b>
	<?php echo CHtml::encode($data->sp_d_description); ?>
	<br />


</div>