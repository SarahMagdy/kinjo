<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pd-config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id', CHtml::listData(
				PdConfig::model()->findAll('parent_id IS NULL AND conf_buid = '.Yii::app()->session['User']['UserBuid']), 'cfg_id', 'name') , array('prompt' => 'Select a Parent')); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
	
	<!--<div class="row">
		<?php echo $form->labelEx($model,'conf_buid'); ?>
		<?php echo $form->textField($model,'conf_buid'); ?>
		<?php echo $form->error($model,'conf_buid'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row" id ="ValDiv">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row" id="ChkradDiv">
		<?php echo $form->labelEx($model,'conf_chkrad'); ?>
		<?php //echo $form->textField($model,'conf_chkrad'); ?>
		<?php echo $form->dropDownList($model, 'conf_chkrad', array('0'=>'Checkable','1'=>'Radio') , array('prompt' => 'Checkable OR Radio')); ?>
		<?php echo $form->error($model,'conf_chkrad'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
	
	$(document).ready(function(){
		$('#PdConfig_parent_id').change();
	});	
	
	$('#PdConfig_parent_id').change(function(){
		if($('#PdConfig_parent_id').val() > 0){
			$('#ValDiv').show();
			$('#ChkradDiv').hide();
		} else {
			$('#ValDiv').hide();
			$('#ChkradDiv').show();
		}
		
	});
</script>