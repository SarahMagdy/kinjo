<?php
/* @var $this M_OrdersController */
/* @var $data M_Orders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ord_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ord_id), array('view', 'id'=>$data->ord_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cid')); ?>:</b>
	<?php echo CHtml::encode($data->cid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('app_type')); ?>:</b>
	<?php echo CHtml::encode($data->app_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ord_total')); ?>:</b>
	<?php echo CHtml::encode($data->ord_total); ?>
	<br />


</div>