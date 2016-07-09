<?php
/* @var $this BillsController */
/* @var $data Bills */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->bill_id), array('view', 'id'=>$data->bill_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->bill_owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_due_date')); ?>:</b>
	<?php echo CHtml::encode($data->bill_due_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_pay_date')); ?>:</b>
	<?php echo CHtml::encode($data->bill_pay_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_amount')); ?>:</b>
	<?php echo CHtml::encode($data->bill_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_disc')); ?>:</b>
	<?php echo CHtml::encode($data->bill_disc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bill_currency_id')); ?>:</b>
	<?php echo CHtml::encode($data->bill_currency_id); ?>
	<br />


</div>