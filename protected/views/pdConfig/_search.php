<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cfg_id'); ?>
		<?php echo $form->textField($model,'cfg_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parent_id'); ?>
		<?php //echo $form->textField($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model, 'parent_id', CHtml::listData(
				PdConfig::model()->findAll(array("condition"=>"parent_id IS NULL ")), 'cfg_id', 'name') , array('prompt' => 'Select a Config')); ?>
	</div>
	
	<!--
	<div class="row">
			<?php echo $form->label($model,'conf_buid'); ?>
			<?php echo $form->textField($model,'conf_buid'); ?>
		</div>-->
	

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'conf_chkrad'); ?>
		<?php //echo $form->textField($model,'conf_chkrad'); ?>
		<?php echo $form->dropDownList($model, 'conf_chkrad', array('0'=>'Checkable','1'=>'Radio') , array('prompt' => 'Checkable OR Radio')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->