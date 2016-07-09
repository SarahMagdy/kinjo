<?php
/* @var $this OrdersDetailsController */
/* @var $data OrdersDetails */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ord_det_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ord_det_id), array('view', 'id'=>$data->ord_det_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ord_id')); ?>:</b>
	<?php echo CHtml::encode($data->ord_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ord_buid')); ?>:</b>
	<?php echo CHtml::encode($data->ord_buid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reserved_bu')); ?>:</b>
	<?php echo CHtml::encode($data->reserved_bu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::encode($data->pid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item')); ?>:</b>
	<?php echo CHtml::encode($data->item); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qnt')); ?>:</b>
	<?php echo CHtml::encode($data->qnt); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('disc')); ?>:</b>
	<?php echo CHtml::encode($data->disc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fees')); ?>:</b>
	<?php echo CHtml::encode($data->fees); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('final_price')); ?>:</b>
	<?php echo CHtml::encode($data->final_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convert_price')); ?>:</b>
	<?php echo CHtml::encode($data->convert_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dollor_price')); ?>:</b>
	<?php echo CHtml::encode($data->dollor_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pay_type')); ?>:</b>
	<?php echo CHtml::encode($data->pay_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cust_billingAddr')); ?>:</b>
	<?php echo CHtml::encode($data->cust_billingAddr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cust_shipAddr')); ?>:</b>
	<?php echo CHtml::encode($data->cust_shipAddr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_source')); ?>:</b>
	<?php echo CHtml::encode($data->app_source); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('close_date')); ?>:</b>
	<?php echo CHtml::encode($data->close_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>