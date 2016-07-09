<?php
/* @var $this OffersController */
/* @var $model Offers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'offers-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'pro_name'); ?>
		<!--<?php echo $form->textField($model,'pid'); ?>-->
		<?php echo $form->dropDownList($model, 'pid', CHtml::listData(
				Products::model()->findAll(array('condition'=>'buid = '.Yii::app()->session['User']['UserBuid'])), 'pid', 'title') , array('prompt' => 'Select a Product')); ?>
		<?php echo $form->error($model,'pid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<!--<?php echo $form->textField($model,'text',array('size'=>60,'maxlength'=>200)); ?>-->
		<?php echo $form->textArea($model, 'text',array('maxlength' => 300, 'rows' => 4, 'cols' => 70)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->textField($model,'discount',array('size'=>6,'maxlength'=>6)); ?>%
		<?php echo $form->error($model,'discount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<!--<?php echo $form->textField($model,'active'); ?>-->
		<?php
		    echo $form->radioButtonList($model,'active',array('0'=>'NO', '1'=>'YES'), array(
		    'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'')));
		?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'from'); ?>
		<!--<?php echo $form->textField($model,'from'); ?>-->
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
		<?php echo $form->error($model,'from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'to'); ?>
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
		<?php echo $form->error($model,'to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'scheduled'); ?>
		<?php echo $form->textField($model,'scheduled'); ?>
		<?php echo $form->error($model,'scheduled'); ?>
	</div>
	<!--
		<div class="row">
			<?php echo $form->labelEx($model,'created'); ?>
			<?php echo $form->textField($model,'created'); ?>
			<?php echo $form->error($model,'created'); ?>
		</div>
	-->
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'OfferBtn','OFFID'=>$model->isNewRecord ? 0 : $model->ofid)); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	
	$('.OfferBtn').click(function(event){
		
		event.preventDefault();
		
		if($("#Offers_active_1").is(":checked")){
			if($('#Offers_pid').val() > 0 && $('#from').val()!= '' && $('#to').val()!= ''){
				
				var d = {
					
					ID:$(this).attr('OFFID'),
					ProID:$('#Offers_pid').val(),
					Frm:$('#from').val(),
					To:$('#to').val()
				};
				
				$.post('/index.php/Offers/AjaxChkOfferValid',d,function(data){
					
					if(data == 'TRUE'){
						
						if(confirm('There is active Offer for ' + $('#Offers_pid option:selected').text() + ' in this period , it will inactivie ')){
							$('#offers-form').submit();
						}
						
					} else {
						$('#offers-form').submit();
					}
				});
			}
			
		}else{
			$('#offers-form').submit();
		}
		
	});
	
</script>



