<?php
/* @var $this CpanelController */
/* @var $model Cpanel */
/* @var $form CActiveForm */

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cpanel-form',
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
		
		<?php // echo $form->textField($model,'buid'); ?>
		
		<?php if(Yii::app()->session['User']['UserRoleID'] == '1'):?>
			<?php echo $form->labelEx($model,'buid'); ?>
			<?php echo $form->dropDownList($model, 'buid', CHtml::listData(
					BuAccounts::model()->findAll(), 'accid', 'fname') , array('prompt' => 'Select Account' ));?>
			
		<?php endif;?>
			
		<?php if(Yii::app()->session['User']['UserRoleID']=='2'):?>
			
				<?php 
					if($model->level == 1 || $model->isNewRecord){
						echo $form->labelEx($model,'buid'); 
						echo $form->dropDownList($model, 'buid', CHtml::listData(
						BusinessUnit::model()->findAll('accid = '.Yii::app()->session['User']['UserOwnerID']), 'buid', 'title') , array('prompt' => 'Select a Business Unit' ));
					}
				?> 
		<?php endif;?>
		<?php echo $form->error($model,'buid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<?php if($model->isNewRecord):?>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('size'=>25,'maxlength'=>25)); ?>
			
			<?php echo $form->labelEx($model,'confirmpassword'); ?>
			<?php echo $form->passwordField($model,'confirmpassword',array('size'=>25,'maxlength'=>25)); ?>
			
			<?php echo $form->error($model,'password'); ?>
		</div>
	<?php endif;?>
	<?php 
			if(!$model->isNewRecord && Yii::app()->session['User']['UserID'] == $model->cp_id){
				echo $form->labelEx($model,'role_id');	
				echo '<select id="Cpanel_role_id" name="Cpanel[role_id]" disabled>
						<option value="'.Yii::app()->session['User']['UserRoleID'].'">'.Yii::app()->session['User']['UserRoleName'].'</option>
					 </select>';
			} else {
				if(Yii::app()->session['User']['UserRoleID'] == '2'){
					  echo $form->labelEx($model,'role_id');
					  echo $form->dropDownList($model, 'role_id', CHtml::listData(
						Roles::model()->findAll(array('condition'=>'role_id > 2')), 'role_id', 'role_name') , array('prompt' => 'Select Roles' ));
					  echo $form->error($model,'role_id'); 
				 }
			}
	?>		
		  
	<div class="row">
		<?php echo $form->labelEx($model,'photo'); ?>
		<?php //echo $form->textField($model,'photo',array('size'=>60,'maxlength'=>200)); ?>
		<?php //echo CHtml::activeFileField($model,'photo'); ?>
		 <input type="file" size="32" name="photo" value="">
		<?php echo $form->error($model,'photo'); ?>
		 <select id='Dimensions' name="Cpanel[Dimensions]" >
			<!-- <option value="">Select Dimensions</option> -->
			<option value="200x200" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '200x200'):?>selected<?php endif;?> <?php endif;?> > 200 x 200 </option>
			<option value="400x400" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '400x400'):?>selected<?php endif;?> <?php endif;?> > 400 x 400 </option>
		</select>
	</div>
	<?php if(!$model->isNewRecord):?>
		<div class="row">
		     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/cpanel/thumbnails/'.$model->photo , 
		     						 "photo" ); 
		     ?>  
		</div>
	<?php endif;?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
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

	<div class="row" hidden>
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>
	
		<!--
		<?php
			// $model->level = 1;
			// $model->isNewRecord ? $model->level = 1: $model->level = $model->level ; 
            //$levelStatus = array('0'=>'Owner', '1'=>'Data Entry');
		    //echo $form->radioButtonList($model,'level',$levelStatus, array( 'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'') 
			//));
		?>
		<?php // echo $form->error($model,'level'); ?>
	</div>-->

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

<script>
	// $('#Cpanel_level_1').attr('checked' , 'true');
	// document.getElementsByTagName("Cpanel[level]");
	// document.getElementsByName('Cpanel[level]').style('disabled','disabled');
	// alert($('input:radio[name = Cpanel[level]]:checked').val());
	
	// $('.My_Radios').attr('disabled','true');
	
	
</script>


