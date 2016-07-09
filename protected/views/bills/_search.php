<?php
/* @var $this BillsController */
/* @var $model Bills */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'bill_id'); ?>
		<?php echo $form->textField($model,'bill_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_owner_id'); ?>
		<?php echo $form->textField($model,'bill_owner_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_due_date'); ?>
		<?php echo $form->textField($model,'bill_due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_pay_date'); ?>
		<?php echo $form->textField($model,'bill_pay_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_amount'); ?>
		<?php echo $form->textField($model,'bill_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_disc'); ?>
		<?php echo $form->textField($model,'bill_disc'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bill_currency_id'); ?>
		<?php echo $form->textField($model,'bill_currency_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->