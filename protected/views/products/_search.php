<?php
/* @var $this ProductsController */
/* @var $model Products */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'pid'); ?>
		<?php echo $form->textField($model,'pid'); ?>
		
		
		
	</div>
<!--
	<div class="row">
		<?php //echo $form->label($model,'buid'); ?>
		<?php //echo $form->textField($model,'buid'); ?>
		
		
		<?php echo $form->label($model,'business_unit_title'); ?>
		<?php echo $form->dropDownList($model, 'buid', CHtml::listData(
				BusinessUnit::model()->findAll(), 'buid', 'title') , array('prompt' => 'Select BusinessUnit')); ?>
		
	</div>
-->
	<div class="row">
		<?php echo $form->label($model,'catsub_title'); ?>
		<?php //echo $form->textField($model,'csid'); ?>
		
		<?php echo $form->dropDownList($model, 'csid', CHtml::listData(
				Catsub::model()->findAll(array('condition'=>'catsub_buid = '.Yii::app()->session['User']['UserBuid'])), 'csid', 'title') , array('prompt' => 'Select Category')); ?>
				
				
	</div>
	
	

	<div class="row">
		<?php echo $form->label($model,'sku'); ?>
		<?php echo $form->textField($model,'sku',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'discription'); ?>
		<?php echo $form->textField($model,'discription',array('size'=>60,'maxlength'=>500)); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<!--<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'instock'); ?>
		<?php echo $form->textField($model,'instock'); ?>
	</div>

	<div class="row">
		<?php //echo $form->label($model,'discount'); ?>
		<?php //echo $form->textField($model,'discount',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'qrcode'); ?>
		<?php echo $form->textField($model,'qrcode',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nfc'); ?>
		<?php echo $form->textField($model,'nfc',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hash'); ?>
		<?php echo $form->textField($model,'hash',array('size'=>60,'maxlength'=>250)); ?>
	</div>-->

	<!--<div class="row">
		<?php echo $form->label($model,'bookable'); ?>
		<?php echo $form->textField($model,'bookable'); ?>
	</div>-->

	<!--<div class="row">
		<?php echo $form->label($model,'created');$form->label($model,'rating'); ?>
		<?php echo $form->textField($model,'rating',array('size'=>5,'maxlength'=>5)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php //echo $form->textField($model,'created'); ?>
		
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'created',
			    'name'=>'created',
			    'options' => array(
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2000-01-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			    ),
			    //'htmlOptions' => array(
			      //  'size' => '10',         // textField size
			        //'maxlength' => '10',    // textField maxlength
			    //),
			));
		?>

</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->