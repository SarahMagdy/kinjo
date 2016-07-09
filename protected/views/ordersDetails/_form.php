<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'orders-details-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ord_id'); ?>
		<?php echo $form->textField($model,'ord_id'); ?>
		<?php echo $form->error($model,'ord_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ord_buid'); ?>
		<?php echo $form->textField($model,'ord_buid'); ?>
		<?php echo $form->error($model,'ord_buid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item'); ?>
		<?php echo $form->textField($model,'item',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'item'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qnt'); ?>
		<?php echo $form->textField($model,'qnt'); ?>
		<?php echo $form->error($model,'qnt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'disc'); ?>
		<?php echo $form->textField($model,'disc'); ?>
		<?php echo $form->error($model,'disc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fees'); ?>
		<?php echo $form->textField($model,'fees'); ?>
		<?php echo $form->error($model,'fees'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'final_price'); ?>
		<?php echo $form->textField($model,'final_price'); ?>
		<?php echo $form->error($model,'final_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->