<?php
/* @var $this CatsubController */
/* @var $model Catsub */
/* @var $form CActiveForm */

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'catsub-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'catsub_title'); ?>
		<?php //echo $form->dropDownList($model, 'parent_id', CHtml::listData(
				//Catsub::model()->findAll('parent_id IS NULL'), 'csid', 'title') , array('prompt' => 'Select a Category')); 
				echo $form->dropDownList($model, 'parent_id', CHtml::listData(
				Catsub::model()->findAll('parent_id IS NULL AND catsub_buid ='.Yii::app()->session['User']['UserBuid'].' 
										  AND csid NOT IN (SELECT csid FROM products WHERE buid ='.Yii::app()->session['User']['UserBuid'].')'), 'csid', 'title') , array('prompt' => 'Select a Category' )); 
		?>
		<?php echo $form->error($model,'catsub_title'); ?>
	</div>
	
	<?php if($type != 'ajax'):?>
	<!--<div class="row">
		<?php // echo $form->labelEx($model,'catsub_buid'); ?>
		<?php //echo $form->textField($model,'catsub_buid'); ?>
		<?php //echo $form->dropDownList($model, 'catsub_buid', CHtml::listData(
				//BusinessUnit::model()->findAll('buid = '.Yii::app()->session['User']['UserBuid']), 'buid', 'title') , array('prompt' => 'Select a Business Unit' )); ?>
		<?php //echo $form->error($model,'catsub_buid'); ?>
	</div>-->
	<?php endif;?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'desription'); ?>
		<?php echo $form->textArea($model, 'desription',array('maxlength' => 300, 'rows' => 4, 'cols' => 70)); ?>
		<?php echo $form->error($model,'desription'); ?>
	</div>

	<?php if($type != 'ajax'):?>
		<div class="row">
	        <?php echo $form->labelEx($model,'img'); ?>
	        <?php //echo CHtml::activeFileField($model,'img_url'); ?>
	        <input type="file" size="32" name="img_url" value="">
	        <?php echo $form->error($model,'img'); ?>
	        
	        <select id='Dimensions' name="Catsub[Dimensions]">
				<option value="200x200" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '200x200'):?>selected<?php endif;?> <?php endif;?> > 200 x 200 </option>
				<option value="400x400" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '400x400'):?>selected<?php endif;?> <?php endif;?> > 400 x 400 </option>
			</select>
		
		</div>
	<?php endif;?>
	
	<?php if(!$model->isNewRecord):?>
		<div class="row">
		     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/upload/catsub/thumbnails/'.$model->img_url,"img"); ?>
	    </div>
	<?php endif;?>
	<!--
	<div class="row">
		<?php echo $form->labelEx($model,'img_thumb'); ?>
		<?php echo $form->textField($model,'img_thumb',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'img_thumb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'img_url'); ?>
		<?php echo $form->textField($model,'img_url',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'img_url'); ?>
	</div>
-->
	<!--<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>-->

	<div class="row buttons">
		<?php echo $type == 'ajax'?CHtml::ajaxButton ('Create','',array('type'=>'POST','url'=>CController::createUrl('/catsub/AjaxCreate')),array('id'=>'CatAjaxBtn')):CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
	
	$('#CatAjaxBtn').click(function(e){
		
		var data = {
			parent_id:$('#Catsub_parent_id').val(),
			title:$('#Catsub_title').val(),
			desription:$('#Catsub_desription').val(),
			buid:$('#Products_buid').val(),
			Catsub:true,
		};
		
		$.post('/index.php/catsub/AjaxCreate',data,function(data){
	  			
  			var ParentID = $('#Catsub_parent_id').val();
  			if (ParentID > 0) {
  				
  				if($("#Products_csid option.opt_"+ParentID).length > 0){
  					
  					$("#Products_csid option.opt_"+ParentID+":Last").after('<option value="'+data+'" class="opt_'+ParentID+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;'+$("#Catsub_title").val()+'</option>');
  				
  				} else {
  					
  					$("#Products_csid option[value='"+ParentID+"']").after('<option value="'+data+'" class="opt_'+ParentID+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;'+$("#Catsub_title").val()+'</option>');
  				}
  				
  			} else {
  				
  				$("#Products_csid").append('<option value="'+data+'" style="text-shadow: 0px 0px 0px black;">'+$("#Catsub_title").val()+'</option>');
  			}
  			//$('#Products_csid').trigger("chosen:updated");
  			//$("#Products_csid").val(data).selectmenu('refresh', true);
  			$('#AddCat').hide();
  			$('#Products_csid').val(data.trim());
  			
  		});
	});
	
</script>