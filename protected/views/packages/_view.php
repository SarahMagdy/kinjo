<?php
/* @var $this PackagesController */
/* @var $data Packages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('pkgid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->pkgid), array('view', 'id'=>$data->pkgid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('currency')); ?>:</b>
	<?php echo CHtml::encode($data->currency); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />


</div>