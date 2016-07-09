<?php
/* @var $this OffersController */
/* @var $model Offers */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ofid'); ?>
		<?php echo $form->textField($model,'ofid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pro_name'); ?>
		<?php echo $form->dropDownList($model, 'pid', CHtml::listData(
				Products::model()->findAll(array('condition'=>'buid = '.Yii::app()->session['User']['UserBuid'])), 'pid', 'title') , array('prompt' => 'Select a Product')); ?>
		<!--<?php echo $form->textField($model,'pid'); ?>-->
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'text'); ?>
		<?php echo $form->textField($model,'text',array('size'=>60,'maxlength'=>200)); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'discount'); ?>
		<?php echo $form->textField($model,'discount',array('size'=>6,'maxlength'=>6)); ?>%
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
		<!--<?php echo $form->textField($model,'active'); ?>-->
		<?php echo $form->dropDownList($model, 'active',
				 array(''=>'------','0' => 'No' ,'1' => 'Yes')); ?>
				 
			
	</div>

	<div class="row">
		<?php echo $form->label($model,'from'); ?>
		<!--<?php echo $form->textField($model,'from'); ?>-->
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'from',
			    'name'=>'from',
			    'options' => array(
			       // 'showOn' => 'both',             // also opens with a button
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        //'showOtherMonths' => true,      // show dates in other months
			        //'selectOtherMonths' => true,    // can seelect dates in other months
			        //'changeYear' => true,           // can change year
			        //'changeMonth' => true,          // can change month
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2000-01-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			        //'showButtonPanel' => true,      // show button panel
			    ),
			    //'htmlOptions' => array(
			      //  'size' => '10',         // textField size
			        //'maxlength' => '10',    // textField maxlength
			    //),
			));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'to'); ?>
		<!--<?php echo $form->textField($model,'to'); ?>-->
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'to',
			    'name'=>'to',
			    'options' => array(
			       // 'showOn' => 'both',             // also opens with a button
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        //'showOtherMonths' => true,      // show dates in other months
			        //'selectOtherMonths' => true,    // can seelect dates in other months
			        //'changeYear' => true,           // can change year
			        //'changeMonth' => true,          // can change month
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2000-01-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			        //'showButtonPanel' => true,      // show button panel
			    ),
			    //'htmlOptions' => array(
			      //  'size' => '10',         // textField size
			        //'maxlength' => '10',    // textField maxlength
			    //),
			));
		?>
	</div>

	<!--<div class="row">
		<?php echo $form->label($model,'scheduled'); ?>
		<?php echo $form->textField($model,'scheduled'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->