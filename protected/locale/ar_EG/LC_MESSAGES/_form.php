<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bu-accounts-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
<?php  //echo Yii::t( 'app', 'Login' ) ;
//echo putenv("LANG");
echo _("Title");
// echo _("Name");

?>
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pkg_id'); ?>
		<!--<?php echo $form->textField($model,'pkg_id'); ?>-->
		
		<?php echo $form->dropDownList($model, 'pkg_id', CHtml::listData(
				Packages::model()->findAll(), 'pkgid', 'title') , array('prompt' => 'Select a Package')); ?>
		
		<?php echo $form->error($model,'pkg_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bill_cycle_id'); ?>
		<!--<?php echo $form->textField($model,'bill_cycle_id'); ?>-->
		<?php echo $form->dropDownList($model, 'bill_cycle_id', CHtml::listData(
				BillingCycle::model()->findAll(), 'bcid', 'duration') , array('prompt' => '- Select Duration -')); ?>
		<?php echo $form->error($model,'bill_cycle_id'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'country_id'); ?>
		<!--<?php echo $form->textField($model,'country_id'); ?>-->
		<?php echo $form->dropDownList($model, 'country_id', CHtml::listData(
				Country::model()->findAll(), 'country_id', 'name') , array('prompt' => '- Select Country -')); ?>
		<?php echo $form->error($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gender'); ?>
		<?php //echo $form->textField($model,'gender',array('size'=>10,'maxlength'=>10)); ?>
		
		<?php echo $form->dropDownList($model, 'gender', array('Male'=>'Male','Female'=>'Female') , array('prompt' => '- Select Gender -')); ?>
		
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'photo'); ?>
		<!--<?php echo $form->textField($model,'photo',array('size'=>60,'maxlength'=>200)); ?>-->
		<?php // echo CHtml::activeFileField($model,'photo'); ?>
		<input type="file" size="32" name="photo" value="">
		<?php echo $form->error($model,'photo'); ?>
		
		<select id='Dimensions' name="BuAccounts[Dimensions]" required>
			<option value="">Select Dimensions</option>
			<option value="200x200" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '200x200'):?>selected<?php endif;?> <?php endif;?> > 200 x 200 </option>
			<option value="400x400" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '400x400'):?>selected<?php endif;?> <?php endif;?> > 400 x 400 </option>
		</select>
		<!--<input id="fileupload" type="file" name="files[]" multiple
		    data-url="/images/upload/blueimp-file-upload.jquery.json"
		    data-sequential-uploads="true"
		    data-form-data='{"script": "true"}'>-->
	</div>
	
	<?php if(!$model->isNewRecord):?>
	<div class="row">
	     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/bu_accounts/thumbnails/'.$model->photo , 
	     						 "photo" ); 
	     ?>  
	</div>
	<?php endif;?>
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'tel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'has_group'); ?>
		<!--<?php echo $form->textField($model,'has_group'); ?>-->
		
		<?php //echo $form->radioButton($model,'has_group',array('value'=>'1')) . 'YES'; ?>
		<?php //echo $form->radioButton($model,'has_group',array('value'=>'0')) . 'NO'; ?>
		
		<?php
            $accountStatus = array('0'=>'NO', '1'=>'YES');
            //echo $form->radioButtonList($model,'has_group',$accountStatus, array(
			//'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'')));
    
		    echo $form->radioButtonList($model,'has_group',$accountStatus, array(
		    'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'')));
		?>
		
		<?php echo $form->error($model,'has_group'); ?>
	</div>
	
	
	<div class="row">
		
		<?php echo $form->labelEx($model,'start_date'); ?>
		<!--<?php echo $form->textField($model,'start_date'); ?>-->
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'model' => $model,
			    'attribute' => 'start_date',
			    'name'=>'start_date',
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
			        // 'readonly'=>true		  // to disable the datepicker
			    //),
			));
		?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<!--<?php echo $form->textField($model,'status'); ?>-->
		<?php echo $form->dropdownlist($model, 'status', 
									 array('1'=>'active','2'=> 'disabled') , 
									 array('prompt' => 'Select Status')); ?>

		<?php echo $form->error($model,'status'); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	
<?php $this->endWidget(); ?>

</div><!-- form -->