<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'billing-cycle-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'bc_duration'); ?>
		<?php echo $form->textField($model,'bc_duration'); ?>
		<?php echo $form->error($model,'bc_duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bc_type'); ?>
		<?php // echo $form->textField($model,'bc_type'); 
			echo $form->dropDownList($model, 'bc_type', array('2'=>'Years','1'=>'Months' , '0'=>'Days') , array('prompt' => '- Select Type -'));
		?>
		<?php echo $form->error($model,'bc_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->