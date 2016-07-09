<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<!--
	<div class="row">
		<?php echo $form->label($model,'buid'); ?>
		<?php echo $form->textField($model,'buid'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->label($model,'accid'); ?>
	<!--	<?php echo $form->dropDownList($model, 'accid', CHtml::listData(
				BuAccounts::model()->findAllBySql('Select accid , concat(fname," ",lname) name ,fname,lname from bu_accounts'), 'accid', 'fname') , array('prompt' => 'Select a Account')); ?>
		<?php echo $form->textField($model,'accid'); ?>-->
		<select name='BusinessUnit[accid]' id='BusinessUnit_accid'>
			<option value="">Select a Account</option>
			<?php $AccData = BuAccounts::model()->findAll();?>
			<?php foreach ($AccData as $key => $row):?>
					<option value="<?=$row['accid'];?>"><?=$row['fname'].' '.$row['lname'];?></option>
			<?php endforeach;?>
		</select>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pkg_id'); ?>
		<?php echo $form->dropDownList($model, 'pkg_id', CHtml::listData(
			  Packages::model()->findAll(), 'pkgid', 'title') , array('prompt' => '- Select Packages -')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'long'); ?>
		<?php echo $form->textField($model,'long',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lat'); ?>
		<?php echo $form->textField($model,'lat',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<?php $type = isset(Yii::app()->session['User']['UserType'])?Yii::app()->session['User']['UserType']:'';?>
	<?php if($type == 'admin'):?>
		<div class="row">
			<?php echo $form->label($model,'active'); ?>
			<select name='BusinessUnit[active]' id='BusinessUnit active'>
				<option value="0" <?php if($model->active == '0'):?>selected<?php endif;?>>Active</option>
				<option value="1" <?php if($model->active == '1'):?>selected<?php endif;?>>Not Active</option>
			</select>
		</div>
	<?php endif;?>
<!--	
	<div class="row">
		<?php echo $form->label($model,'currency_code'); ?>
		<?php echo $form->textField($model,'currency_code',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'membership'); ?>
		<?php echo $form->textField($model,'membership'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'logo'); ?>
		<?php echo $form->textField($model,'logo',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'urlid'); ?>
		<?php echo $form->textField($model,'urlid',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->textField($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'site'); ?>
		<?php echo $form->textField($model,'site',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'statid'); ?>
		<?php echo $form->textField($model,'statid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'apiKey'); ?>
		<?php echo $form->textField($model,'apiKey',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rating'); ?>
		<?php echo $form->textField($model,'rating',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>
-->
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->