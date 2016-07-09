<?php
/* @var $this BillsController */
/* @var $model Bills */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bills-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_owner_id'); ?>
		<?php echo $form->textField($model,'bill_owner_id'); ?>
		<?php echo $form->error($model,'bill_owner_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_due_date'); ?>
		<?php echo $form->textField($model,'bill_due_date'); ?>
		<?php echo $form->error($model,'bill_due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_pay_date'); ?>
		<?php echo $form->textField($model,'bill_pay_date'); ?>
		<?php echo $form->error($model,'bill_pay_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_amount'); ?>
		<?php echo $form->textField($model,'bill_amount'); ?>
		<?php echo $form->error($model,'bill_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_disc'); ?>
		<?php echo $form->textField($model,'bill_disc'); ?>
		<?php echo $form->error($model,'bill_disc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_currency_id'); ?>
		<?php echo $form->textField($model,'bill_currency_id'); ?>
		<?php echo $form->error($model,'bill_currency_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->