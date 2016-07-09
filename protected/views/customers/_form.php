<?php
/* @var $this CustomersController */
/* @var $model Customers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customers-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fname'); ?>
		<?php echo $form->textField($model,'fname',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'fname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'lname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gender'); ?>
		<?php //echo $form->textField($model,'gender'); ?>
		<?php
            $genderStatus = array('0'=>'Male', '1'=>'Female');
    
		    echo $form->radioButtonList($model,'gender',$genderStatus, array(
		    'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'')));
		?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birthdate'); ?>
		<?php //echo $form->textField($model,'birthdate'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'birthdate',
			    'name'=>'birthdate',
			    'options' => array(
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'selectOtherMonths' => true,    // can seelect dates in other months
			        'yearRange' => '1920:2099',     // range of year
			        'minDate' => '1920-01-01',      // minimum date
			        'maxDate' => 'today',      // maximum date
			    )
			));
		?>
		<?php echo $form->error($model,'birthdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php //echo $form->textField($model,'country_id'); ?>
		<?php echo $form->dropDownList($model, 'country_id', CHtml::listData(
				Country::model()->findAll(), 'country_id', 'name') , array('prompt' => '- Select Country -')); ?>
		<?php echo $form->error($model,'country_id'); ?>
		
		<?php echo $form->error($model,'country_id'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'social_id'); ?>
		<?php echo $form->textField($model,'social_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'social_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'google_id'); ?>
		<?php echo $form->textField($model,'google_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'google_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fav_id'); ?>
		<?php echo $form->textField($model,'fav_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'fav_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div> -->

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->