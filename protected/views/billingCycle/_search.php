<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'bcid'); ?>
		<?php echo $form->textField($model,'bcid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bc_duration'); ?>
		<?php echo $form->textField($model,'bc_duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bc_type'); ?>
		<?php // echo $form->textField($model,'bc_type'); 
			echo $form->dropDownList($model, 'bc_type', array('2'=>'Years','1'=>'Months' , '0'=>'Days') , array('prompt' => '- Select Type -'));
			
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->