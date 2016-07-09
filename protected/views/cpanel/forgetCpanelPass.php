<?php
/* @var $this CpanelController */
/* @var $model Cpanel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Forget-password-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,	
	)); 
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php if(isset($error_message)):?>
		<div class="errorSummary">
			<p>Please fix the following input errors:</p>
			<ul>
				<li>
				<?php echo $error_message; ?>
				</li>
			</ul>
		</div>
	<?php endif;?>
	
	  <div class="row"> 
	  	<?php echo $form->labelEx($model,'password'); ?> 
	  	<?php echo $form->passwordField($model,'password'); ?> 
	  
	  	<?php echo $form->labelEx($model,'confirmpassword'); ?> 
	  	<?php echo $form->passwordField($model,'confirmpassword'); ?> 
	  	<?php echo $form->error($model,'confirmpassword'); ?> 
	  </div>
	
	
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Forgetpassword' : 'Change'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
