<?php
/* @var $this CustomersController */
/* @var $model Customers */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cid'); ?>
		<?php echo $form->textField($model,'cid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fname'); ?>
		<?php echo $form->textField($model,'fname',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gender'); ?>
		<?php //echo $form->textField($model,'gender'); ?></br>
		<?php
            $genderStatus = array('0'=>'Male', '1'=>'Female');
		    echo $form->radioButtonList($model,'gender',$genderStatus, array(
		    'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'') ));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'birthdate'); ?>
		<?php //echo $form->textField($model,'birthdate'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'birthdate',
			    'name'=>'birthdate',
			    'options' => array(
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'yearRange' => '1920:2099',     // range of year
			        'minDate' => '1920-01-01',      // minimum date
			        'maxDate' => 'today',      // maximum date
			    ),
			));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country_id'); ?>
		<?php //echo $form->textField($model,'country_id'); ?>
		<?php echo $form->dropDownList($model, 'country_id', CHtml::listData(
				Country::model()->findAll(), 'country_id', 'name') , array('prompt' => 'Select Country')); ?>
		
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'social_id'); ?>
		<?php echo $form->textField($model,'social_id',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'google_id'); ?>
		<?php echo $form->textField($model,'google_id',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fav_id'); ?>
		<?php echo $form->textField($model,'fav_id',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->