<?php
/* @var $this MessagesController */
/* @var $data Messages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('mid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->mid), array('view', 'id'=>$data->mid)); ?>
	<br />
<!--
	<b><?php echo CHtml::encode($data->getAttributeLabel('buid')); ?>:</b>
	<?php echo CHtml::encode($data->buid); ?>
	<br />
-->
	<b><?php echo CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?php echo CHtml::encode($data->message); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />


</div>