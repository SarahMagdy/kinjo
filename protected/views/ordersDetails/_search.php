<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ord_det_id'); ?>
		<?php echo $form->textField($model,'ord_det_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ord_id'); ?>
		<?php echo $form->textField($model,'ord_id'); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'ord_buid'); ?>
		<?php echo $form->textField($model,'ord_buid'); ?>
	</div>-->

	<!--<div class="row">
		<?php echo $form->label($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'item'); ?>
		<!--<?php echo $form->textField($model,'item',array('size'=>60,'maxlength'=>100)); ?>-->
		<?php echo $form->dropDownList($model, 'pid', CHtml::listData(
				Products::model()->findAll(array('condition'=>'buid = '.Yii::app()->session['User']['UserBuid'])), 'pid', 'title') , array('prompt' => 'Select a Product')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'qnt'); ?>
		<?php echo $form->textField($model,'qnt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disc'); ?>
		<?php echo $form->textField($model,'disc'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fees'); ?>
		<?php echo $form->textField($model,'fees'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'final_price'); ?>
		<?php echo $form->textField($model,'final_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->