<?php
/* @var $this PdConfigController */
/* @var $data PdConfig */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cfg_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cfg_id), array('view', 'id'=>$data->cfg_id)); ?>
	<br />
	<?php if($data->parent != null): ?>
		<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
		<?php echo ( $data->parent )? CHtml::encode($data->parent->name) : '--'; ?>
		<br />
	<?php endif; ?>
	
	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('conf_buid')); ?>:</b>
	<?php //echo CHtml::encode($data->conf_buid); ?>
	<?php echo ( $data->confBu )? CHtml::link(CHtml::encode($data->confBu->title) , array('businessUnit/view', 'id'=>$data->conf_buid) ) : '--'; ?>
	<br />-->
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />
	
	<?php if($data->parent != null): ?>
		<b><?php echo CHtml::encode($data->getAttributeLabel('value')); ?>:</b>
		<?php echo CHtml::encode($data->value); ?>
		<br />
	<?php else: ?>
		<b><?php echo CHtml::encode($data->getAttributeLabel('conf_chkrad')); ?>:</b>
		<?php echo CHtml::encode($data->conf_chkrad == 0 ?'Checkable':'Radio'); ?>
		<br />
	<?php endif; ?>
	
	
	
</div>