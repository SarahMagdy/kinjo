<?php
/* @var $this OffersController */
/* @var $data Offers */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ofid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ofid), array('view', 'id'=>$data->ofid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pro_name')); ?>:</b>
	   <?php //echo CHtml::encode($data->pid); ?>
	   <?php echo CHtml::link(CHtml::encode($data->p->title) , "/index.php/products/$data->pid"); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount')); ?>:</b>
	<?php echo CHtml::encode($data->discount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	   <?php //echo CHtml::encode($data->active); ?>
	   <?php echo ($data->active == 1) ? 'Active' : 'Not Active'; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('from')); ?>:</b>
	<?php echo CHtml::encode($data->from); ?>
	<br />

	
	<b><?php echo CHtml::encode($data->getAttributeLabel('to')); ?>:</b>
	<?php echo CHtml::encode($data->to); ?>
	<br />
<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('scheduled')); ?>:</b>
	<?php echo CHtml::encode($data->scheduled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>