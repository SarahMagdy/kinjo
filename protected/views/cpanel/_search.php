<?php
/* @var $this CpanelController */
/* @var $model Cpanel */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<!--<div class="row">
		<?php echo $form->label($model,'cp_id'); ?>
		<?php echo $form->textField($model,'cp_id'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'buid'); ?>
		<?php echo $form->dropDownList($model, 'buid', CHtml::listData(
					BusinessUnit::model()->findAll(array('condition'=>'accid ='.Yii::app()->session['User']['UserOwnerID'])), 'buid', 'title') , array('prompt' => 'Select Business Unit' ));?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'photo'); ?>
		<?php echo $form->textField($model,'photo',array('size'=>60,'maxlength'=>200)); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fname'); ?>
		<?php echo $form->textField($model,'fname',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lname'); ?>
		<?php echo $form->textField($model,'lname',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<?php 
			if(Yii::app()->session['User']['UserRoleID']== '2'){
				  echo '<div class="row">';
				  echo $form->labelEx($model,'role_id');
				  echo $form->dropDownList($model, 'role_id', CHtml::listData(
					Roles::model()->findAll(array('condition'=>'role_id > 2')), 'role_id', 'role_name') , array('prompt' => 'Select Roles' ));
				  echo '</div>';
			}
	?>		
	<!--
	<div class="row">
		<?php echo $form->label($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
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