<?php
/* @var $this ProductsController */
/* @var $model Products */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'business_unit_title'); ?>
		<?php echo $form->dropDownList($model, 'buid', CHtml::listData(
				BusinessUnit::model()->findAll(), 'buid', 'title') , array('prompt' => 'Select a Business')); ?>
		<?php echo $form->error($model,'buid'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'catsub_title'); ?>
		<select name='Products[csid]' id='Products_csid'>
			<option value="">Select a Category</option>
			<?php foreach($CatData AS $key=>$row):?>
				<option value="<?=$row['id'];?>" <?php if($model->csid == $row['id']):?>selected<?php endif;?> <?php if(isset($row['disable'])):?> disabled<?php endif;?> style="text-shadow: 0px 0px 0px black;"><?=$row['title'];?></option>
				<?php if(isset($row['sub'])):?>
					<?php foreach($row['sub'] AS $skey=>$srow):?>
						<option value="<?=$srow['id'];?>" class="opt_<?=$row['id'];?>" <?php if($model->csid == $srow['id']):?>selected<?php endif;?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;<?=$srow['title'];?></option>
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
		</select>
		<?php echo CHtml::link('Add Category','#',array('onclick' => 'FuncCat()')); ?>
		<?php echo $form->error($model,'csid'); ?>
	</div>
	
	<div class="row" id="AddCat" style="display: none;border:1px solid #ccc;padding: 1%;background: #ccc;">
		
		 
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'sku'); ?>
		<?php echo $form->textField($model,'sku',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'sku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->labelEx($model,'instock'); ?>
		<?php echo $form->textField($model,'instock'); ?>
		<?php echo $form->error($model,'instock'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'discount'); ?>
		<?php //echo $form->textField($model,'discount',array('size'=>6,'maxlength'=>6)); ?>
		<?php //echo $form->error($model,'discount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qrcode'); ?>
		<?php echo $form->textField($model,'qrcode',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'qrcode'); ?>
	</div>-->
	
	<div class="row">
		<?php echo $form->labelEx($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'barcode'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'nfc'); ?>
		<?php echo $form->textField($model,'nfc',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'nfc'); ?>
	</div>
	
	
	<?php if(!$model->isNewRecord):?>
		<div class="row" >
			<?php echo $form->labelEx($model,'qrcode', array('style'=>'float:left;display: inline-block;')); ?>
			<!--<img title="QR-Code" src='http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl='.<?php //echo $model->qrcode;?> />-->
			 <?php
		     	echo CHtml::image('http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl='.$model->qrcode , 
		     						 "qrcode" ); ?>
			
		</div>
	<?php endif;?>
	
	
	<?php if(!$model->isNewRecord):?>
		<div class="row">
			<?php echo $form->labelEx($model,'hash'); ?>
			<?php echo $form->textField($model,'hash',array('size'=>60,'maxlength'=>250)); ?>
			<?php echo $form->error($model,'hash'); ?>
		</div>
	<?php endif;?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bookable'); ?>
		<?php
		    echo $form->radioButtonList($model,'bookable',array('0'=>'NO', '1'=>'YES'), array(
		    'labelOptions'=>array('style'=>'display:inline' , 'separator'=>'')));
		?>
		<?php echo $form->error($model,'bookable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discription'); ?>
		<?php echo $form->textArea($model, 'discription',array('maxlength' => 300, 'rows' => 4, 'cols' => 70)); ?>
		<?php echo $form->error($model,'discription'); ?>
	</div>
	<!--
	<div class="row">
		<?php echo $form->labelEx($model,'rating'); ?>
		<?php echo $form->textField($model,'rating',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'rating'); ?>
	</div>	
		
	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>-->
	
		
		
		
		<!-- <?php $this->widget('CMultiFileUpload', array(
                'name' => 'images',
                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Duplicate file!', // useful, i think
                'denied' => 'Invalid file type', // useful, i think
                'max'=>6, // max 10 files
            ));?> -->
       <?php if($model->isNewRecord):?> 
       	<div class="row">
      	 	<?php echo $form->labelEx($model,'img'); ?>
      	 	 <select id='Dimensions' name="Products[Dimensions]">
				<option value="200x200" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '200x200'):?>selected<?php endif;?> <?php endif;?> > 200 x 200 </option>
				<option value="400x400" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '400x400'):?>selected<?php endif;?> <?php endif;?> > 400 x 400 </option>
			</select>
			<br/>
      	 	<input type="file" size="32" name="ProImg[]" value="" />
      	 	<input type="file" size="32" name="ProImg[]" value="" />
      	 	<input type="file" size="32" name="ProImg[]" value="" />
      	 <!--	<input type="file" size="32" name="ProImg[]" value="" />
      	 	<input type="file" size="32" name="ProImg[]" value="" />-->
       	</div>
       <!-- <?php $this->widget('CMultiFileUpload', array(
			     'name' => 'images',
			     'model'=>$model,
			     'attribute'=>'photos',
			     'accept'=>'jpeg|jpg|gif|png',
			     // 'htmlOptions' => array( 'multiple' => 'multiple', ),
			     //'remove'=>'remove',
			     //'remove' => Yii::t('ui', '<div><img  title="Delete" style="float:left;padding-right:5px;" src=' . Yii::app()->request->baseUrl . '/../images/upload/products/thumbnails/trash.png /></div>'),
			     //'htmlOptions'=>array('style'=>'opacity: 100;  height: 80px; width: 118px;cursor: pointer;','size'=>25),
			     // 'options'=>array(
			        // 'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
			        //'afterFileSelect'=>'function(e, v, m){
			        	 // alert("afterFileSelect - "+v) 
			        	 //$("#mydiv").append("<div>dddddd</div>");
					
					//}',
			        // 'onFileAppend'=>'function(e, v, m){ alert("onFileAppend - "+v) }',
			        // 'afterFileAppend'=>'function(e, v, m){ alert("afterFileAppend - "+v) }',
			        // 'onFileRemove'=>'function(e, v, m){ alert("onFileRemove - "+v) }',
			        // 'afterFileRemove'=>'function(e, v, m){ alert("afterFileRemove - "+v) }',
			     // ),
			     'denied'=>'File is not allowed',
			     'max'=>5, // max 5 files	 
			  ));
		?>-->
		
		
		<?php endif;?>
		<!--<div id="mydiv"></div>-->   
		
		
		<!-- <div class="row">
			<?php echo CHtml::activeFileField($model,'img'); ?>
			<?php echo $form->error($model,'img'); ?>
		</div >-->
		
		
		<?php if(!$model->isNewRecord):?>
			<div class="row" >
		     <?php 
		     
		     	$x = ProductsImgs::model()->findAllByAttributes(array('pid'=>$model->pid));
				 if(!empty($x)){
			 		foreach($x as $image => $pic){
			 ?>
			 		<!--<div><img  title="Delete" style="float:left;padding-right:5px;" src=' . Yii::app()->request->baseUrl . '/../images/upload/products/thumbnails/trash.png /></div>-->
		     <div class="row" style="display:inline-block;">
		     <?php
		     	echo CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/products/thumbnails/'.$pic->pimg_thumb , 
		     						 "photo" ); ?>
		     </div>				 
			<?php
					}
			 	}
		     ?>  
		</div>
		
		<!--<?php echo CHtml::link('Images +','#' ,array('onclick' => 'FuncImgs()')); ?>
		<div id="ProdimgsDiv" style="display: none;">
			Product imgs Div
		</div>-->
		
		<?php endif;?>
		
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
	
	function FuncCat () {
		
	  if ($('#AddCat').is(":visible")) {
	  	
	  		$('#AddCat').hide();
	  		
	  } else{
	  	
	  		$('#AddCat').show();
	  		$.post('/index.php/catsub/AjaxCreate',function(data){
	  			$('#AddCat').html(data);
	  			
	  		});
	  	
	  }
	}
	
	function FuncImgs(){
		alert('FuncImgs');
	}
	
</script>