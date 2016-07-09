<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cr_card_id'); ?>
		<?php echo $form->textField($model,'cr_card_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cr_card_owner_id'); ?>
		<?php echo $form->textField($model,'cr_card_owner_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'cr_card_namecard'); ?>
		<?php echo $form->textField($model,'cr_card_namecard',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->label($model,'cr_card_credit'); ?>
		<?php echo $form->textField($model,'cr_card_credit',array('size'=>60,'maxlength'=>250)); ?>
	</div> -->

	<!-- <div class="row">
		<?php echo $form->label($model,'cr_card_cvv'); ?>
		<?php echo $form->textField($model,'cr_card_cvv',array('size'=>60,'maxlength'=>250)); ?>
	</div> -->

	<div class="row">
		<?php echo $form->label($model,'cr_card_expirationDate'); ?>
		<?php echo $form->textField($model,'cr_card_expirationDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cr_card_rank'); ?>
		<?php // echo $form->textField($model,'cr_card_rank'); ?>
		<?php echo $form->dropdownlist($model, 'cr_card_rank', 
									 array('1'=>'Primary Card' , '2'=> 'Secondary Card') , 
									 array('prompt' => 'Card Rank')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->