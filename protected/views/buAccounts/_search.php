<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'accid'); ?>
		<?php echo $form->textField($model,'accid'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'special_deal_id'); ?>
		<?php // echo $form->textField($model,'special_deal_id'); 
			echo $form->dropDownList($model, 'special_deal_id', CHtml::listData(
			SpecialDeals::model()->findAll(), 'sp_d_id', 'sp_d_title') , array('prompt' => 'Select Deal'));
		?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'fname'); ?>
		<?php echo $form->textField($model,'fname',array('size'=>25,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>25,'maxlength'=>45)); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gender'); ?>
		<?php echo $form->textField($model,'gender',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'photo'); ?>
		<?php echo $form->textField($model,'photo',array('size'=>60,'maxlength'=>200)); ?>
	</div>-->

	<!--<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>45,'maxlength'=>45)); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('size'=>25,'maxlength'=>25)); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>25,'maxlength'=>45)); ?>
	</div>

	

	<!-- <div class="row">
		<?php echo $form->label($model,'start_date'); ?>
		<?php echo $form->textField($model,'start_date'); ?>
	</div >-->

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>25)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'bu_acc_TypeID'); ?>
		<?php echo $form->textField($model,'bu_acc_TypeID'); ?>
		<?php // echo $form->textField($model,'has_group' ,array('size'=>25,'maxlength'=>3)); ?>
	</div>
	
	<!--<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->