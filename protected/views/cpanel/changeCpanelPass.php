<?php
/* @var $this CpanelController */
/* @var $model Cpanel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chnage-password-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,	
	)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


		<div class="row"> 
	  	<?php echo $form->labelEx($model,'old_password'); ?> 
	  	<?php echo $form->passwordField($model,'old_password'); ?> 
	  	<?php echo $form->error($model,'old_password'); ?> 
	  </div>
	 
	  <div class="row"> 
	  	<?php echo $form->labelEx($model,'new_password'); ?> 
	  	<?php echo $form->passwordField($model,'new_password'); ?> 
	  	<?php echo $form->error($model,'new_password'); ?> 
	  </div>
	 
	  <div class="row"> 
	  	<?php echo $form->labelEx($model,'repeat_password'); ?> 
	  	<?php echo $form->passwordField($model,'repeat_password'); ?> 
	  	<?php echo $form->error($model,'repeat_password'); ?> 
	  </div>
	
	
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Changepassword' : 'Change'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->