<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'credit-cards-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_owner_id'); ?>
		<?php // echo $form->textField($model,'cr_card_owner_id'); ?>
		<?php 
				echo $form->dropDownList($model, 'cr_card_owner_id', CHtml::listData(
				BuAccounts::model()->findAll(), 'accid', 'fname') , array('prompt' => '- Select Owner -' , 'disabled'=>true)); 
		?>
		
		<?php echo $form->error($model,'cr_card_owner_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_namecard'); ?>
		<?php echo $form->textField($model,'cr_card_namecard',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'cr_card_namecard'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_credit'); ?>
		<?php echo $form->textField($model,'cr_card_credit',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'cr_card_credit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_cvv'); ?>
		<?php echo $form->textField($model,'cr_card_cvv',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'cr_card_cvv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_expirationDate'); ?>
		<?php //echo $form->textField($model,'cr_card_expirationDate'); ?>
		
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'cr_card_expirationDate',
			    'name'=>'cr_card_expirationDate',
			    'options' => array(
			       // 'showOn' => 'both',             // also opens with a button
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2015-02-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			    ),
			));
		?>
		
		<?php echo $form->error($model,'cr_card_expirationDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cr_card_rank'); ?>
		<?php // echo $form->textField($model,'cr_card_rank'); ?>
		<?php echo $form->dropdownlist($model, 'cr_card_rank', 
									 array('1'=>'Primary Card' , '2'=> 'Secondary Card') , 
									 array('prompt' => 'Card Rank')); ?>
		
		
		<?php echo $form->error($model,'cr_card_rank'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->