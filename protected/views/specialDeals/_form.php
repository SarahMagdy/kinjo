<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'special-deals-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_d_bill_cycle_id'); ?>
		<?php //echo $form->textField($model,'sp_d_bill_cycle_id'); 
			// echo $form->dropDownList($model, 'sp_d_bill_cycle_id', CHtml::listData(
				// BillingCycle::model()->findAll(), 'sp_d_id', 'sp_d_title') , array('prompt' => '- Select Deal -'));
				
			$SQL = "SELECT bcid , CASE bc_type WHEN 0 THEN CONCAT(`bc_duration` ,' ','Days')
											   WHEN 1 THEN CONCAT(`bc_duration`,' ' , 'Months')
											   WHEN 2 THEN CONCAT(`bc_duration` , ' ' , 'Years') END AS Cycle
					FROM billing_cycle";
			$result = Yii::app()->db-> createCommand($SQL) -> queryAll();	
			 
		?>
		
		<select name='SpecialDeals[sp_d_bill_cycle_id]' id='SpecialDeals_sp_d_bill_cycle_id'>
			<option value="">Select a Category</option>
			
			<?php foreach($result AS $key=>$row):?>
				
				<option value="<?=$row['bcid'];?>" <?php if($model->sp_d_bill_cycle_id == $row['bcid']):?>selected<?php endif;?>><?=$row['Cycle'];?></option>
			<?php endforeach;?>
		</select>
		<?php echo $form->error($model,'sp_d_bill_cycle_id'); ?>
		
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_d_title'); ?>
		<?php echo $form->textField($model,'sp_d_title',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'sp_d_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_d_amount'); ?>
		<?php echo $form->textField($model,'sp_d_amount'); ?>
		<?php echo $form->error($model,'sp_d_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_d_currency'); ?>
		<?php // echo $form->textField($model,'sp_d_currency',array('size'=>3,'maxlength'=>3));
			$Data = Yii::app()->db->createCommand("SELECT currency_code , currency_name 
												   FROM country") -> queryAll();
		?>
		<select id='SpecialDeals_sp_d_currency' name="SpecialDeals[sp_d_currency]">
			<option value="">-- Currency --</option>
			<?php foreach($Data AS $key=>$row):?>
				 <option value="<?=$row['currency_code']?>" <?php if($model->sp_d_currency == $row['currency_code']):?>Selected<?php endif;?>><?php echo $row['currency_code'].' - '.$row['currency_name']; ?></option>
			<?php endforeach;?>
		</select>
		<?php echo $form->error($model,'sp_d_currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_d_description'); ?>
		<?php // echo $form->textField($model,'sp_d_description',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->textArea($model, 'sp_d_description',array('maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model,'sp_d_description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->