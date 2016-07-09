<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'sp_d_id'); ?>
		<?php echo $form->textField($model,'sp_d_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_d_bill_cycle_id'); ?>
		<?php echo $form->textField($model,'sp_d_bill_cycle_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_d_title'); ?>
		<?php echo $form->textField($model,'sp_d_title',array('size'=>20,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_d_amount'); ?>
		<?php echo $form->textField($model,'sp_d_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_d_currency'); ?>
		<?php echo $form->textField($model,'sp_d_currency',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->label($model,'sp_d_description'); ?>
		<?php echo $form->textField($model,'sp_d_description',array('size'=>60,'maxlength'=>500)); ?>
	</div> -->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->