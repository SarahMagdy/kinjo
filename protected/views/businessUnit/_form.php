<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */
/* @var $form CActiveForm */

?>

<!--<style>
	#PopLoc
	{
       margin: auto;
       top: 0; 
       left: 0;
       bottom: 0; 
       right: 0;
       width: 45%;
       height: 50%;
       padding:2%;
       background:#ffffff;
       display:none;
       position: fixed;
	}
	
</style>-->

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'business-unit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
<?php
// Yii::app()->language = Yii::app()->session['User']['UserLang'];
 // echo Yii::t(Yii::app()->session['User']['LangFile'], 'BuAccount_pkg_id');?>
	<div class="row">
		<?php echo $form->labelEx($model,'accid'); ?>
		<!--<?php echo $form->textField($model,'accid'); ?>-->
		<select name='BusinessUnit[accid]' id='BusinessUnit_accid' class="Bu_Acc"  <?php if(!$model->isNewRecord && Yii::app()->session['User']['UserType']== 'owner'):?> disabled <?php endif;?>>
			<option value="">-- <?php echo $form->labelEx($model,'accid'); ?> --</option>
			<?php foreach ($AccData as $key => $row):?>
				<?php if($model->isNewRecord):?>
					<option value="<?=$row['accid'];?>" SP ="<?=$row['special_deal_id'];?>" ><?=$row['fname'].' '.$row['lname'];?></option>
				<?php else:?>
					<option value="<?=$row['accid'];?>" SP ="<?=$row['special_deal_id'];?>" <?php if($model->accid == $row['accid']):?>selected<?php endif;?>><?=$row['fname'].' '.$row['lname'];?></option>
				<?php endif;?>
			<?php endforeach;?>
		</select>
		<?php echo $form->error($model,'accid'); ?>
	</div>

	<div class="row" id="PackagesDiv" >
		<?php echo $form->labelEx($model,'pkg_id'); ?>
		<?php echo $form->dropDownList($model, 'pkg_id', CHtml::listData(
			  Packages::model()->findAll(), 'pkgid', 'title') , 
			  array('prompt' => '- Select Packages -')); 
		?>
		<?php echo $form->error($model,'pkg_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<!--<div class="row">
		<?php echo $form->labelEx($model,'loc'); ?>
		<?php echo CHtml::button('Location',array('id'=>'btn_loc','onclick'=>'document.getElementById("PopLoc").style.display = "block";')); ?>
		
	</div>
	-->
	<?php if(!$model->isNewRecord):?>	
		<div class="row" style="display:none;">
			<?php echo $form->labelEx($model,'membership'); ?>
			<!--<?php echo $form->textField($model,'membership'); ?>-->
			<select name='BusinessUnit[membership]' id='BusinessUnit membership'>
				<option value="">-- <?php echo $form->labelEx($model,'membership'); ?> --</option>
				<option value="1" <?php if($model->membership == '1'):?>selected<?php endif;?>>membership_1</option>
				<option value="2" <?php if($model->membership == '2'):?>selected<?php endif;?>>membership_2</option>
				<option value="3" <?php if($model->membership == '3'):?>selected<?php endif;?>>membership_3</option>
			</select>
			<?php echo $form->error($model,'level'); ?>
		</div>
	<?php endif;?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'currency_code'); ?>
		<?php
			$Country = Country::model()->findAll(array('condition'=>'currency_code != ""',"select"=>"currency_code,currency_name","group"=>"currency_code","order"=>"currency_code","distinct"=>true))
		;?>
			<select name='BusinessUnit[currency_code]' id='BusinessUnit currency_code'>
				<option value="">-- <?php echo $form->labelEx($model,'currency_code'); ?> --</option>
				<?php foreach($Country AS $key=>$row):?>
					 <?php if(!$model->isNewRecord):?>	
					 	 <option value="<?=$row['currency_code']?>"<?php if($row['currency_code'] == $model->currency_code):?>Selected<?php endif;?>><?php echo $row['currency_code'].' - '.$row['currency_name']; ?></option>
					 <?php else:?>
					 	 <option value="<?=$row['currency_code']?>"<?php if($row['currency_code'] == 'USD'):?>Selected<?php endif;?>><?php echo $row['currency_code'].' - '.$row['currency_name']; ?></option>
					 <?php endif;?>
				<?php endforeach;?>
			</select>
		<?php echo $form->error($model,'currency_code'); ?>
	</div>
	<!--<div class="row">
		<?php echo $form->labelEx($model,'logo'); ?>
		<?php echo $form->textField($model,'logo',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'logo'); ?>
	</div>-->
	<div class="row">
        <?php echo $form->labelEx($model,'logo'); ?>
        <?php //echo CHtml::activeFileField($model,'logo'); ?>
        <input type="file" size="32" name="logo" value="">
        <?php echo $form->error($model,'logo'); ?>
        
        <select id='Dimensions' name="BusinessUnit[Dimensions]">
			<option value="200x200" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '200x200'):?>selected<?php endif;?> <?php endif;?> > 200 x 200 </option>
			<option value="400x400" <?php if(!$model->isNewRecord):?> <?php if($dimensions == '400x400'):?>selected<?php endif;?> <?php endif;?> > 400 x 400 </option>
		</select>
		
	</div>
	<?php if(!$model->isNewRecord):?>
		<div class="row">
		     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/upload/business_unit/thumbnails/'.$model->logo,"logo"); ?>
	    </div>
	<?php endif;?>
	
	<div class="row">
        <?php echo $form->labelEx($model,'cpanel_logo'); ?>
        <input type="file" size="32" name="cpanel_logo" value="">
        <?php echo $form->error($model,'cpanel_logo'); ?>
      
		
	</div>
	<?php if(!$model->isNewRecord):?>
		<div class="row">
		     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/upload/business_unit/Logos/'.$model->cpanel_logo,"Cpanel Logo"); ?>
	    </div>
	<?php endif;?>
	
	<div class="row">
        <?php echo $form->labelEx($model,'urlid'); ?>
        <?php //echo CHtml::activeFileField($model,'logo'); ?>
        <input type="file" size="32" name="urlid" accept="image/png" value="">
        <?php echo $form->error($model,'urlid'); ?>
	</div>
	<?php if(!$model->isNewRecord):?>
		<div class="row">
		     <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/upload/business_unit/icons/'.$model->urlid,"Icon Marker"); ?>
	    </div>
	<?php endif;?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model, 'description',array('maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<?php // if(!$model->isNewRecord):?>
	<?php 
		if(Yii::app()->session['User']['UserType']== 'owner'){
			$disabled = true;	
		}else{
			$disabled = false;
		}
	?>	
	<div class="row" >
		<?php echo $form->labelEx($model,'type'); ?>
		<!--<?php echo $form->textField($model,'type'); ?>-->
		<?php 
			echo $form->dropDownList($model, 'type', CHtml::listData(
			Types::model()->findAll(), 'type_id', 'type_name') , array('prompt' => '- Select Business Type -' , 'disabled'=>$disabled)); 
		?>
		
		<?php echo $form->error($model,'type'); ?>
	</div>
	<?php // endif;?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php $type = isset(Yii::app()->session['User']['UserType'])?Yii::app()->session['User']['UserType']:'';?>
		<select name='BusinessUnit[active]' id='BusinessUnit active'<?php if($type != 'admin'):?>disabled<?php endif;?>>
			<option value="0" <?php if($model->active == '0'):?>selected<?php endif;?>>Active</option>
			<option value="1" <?php if($model->active == '1'):?>selected<?php endif;?>>Not Active</option>
		</select>
		<?php echo $form->error($model,'type'); ?>
	</div>
	
	
	<?php if(!$model->isNewRecord):?>
		<div class="row">
			<?php echo $form->labelEx($model,'apiKey'); ?>
			<?php echo $form->textField($model,'apiKey',array('size'=>60,'maxlength'=>200 ,'readonly'=>'readonly')); ?>
			<?php echo CHtml::button('Regenerate',array('id'=>'btn_loc','onclick'=>'FuncRegenerate()')); ?>
			<?php echo $form->error($model,'apiKey'); ?>
		</div>
	<?php endif;?>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'site'); ?>
		<?php echo $form->textField($model,'site',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'site'); ?>
	</div>
-->
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'statid'); ?>
		<?php echo $form->textField($model,'statid'); ?>
		<?php echo $form->error($model,'statid'); ?>
	</div>
-->
<!--	<div class="row">
		<?php echo $form->labelEx($model,'rating'); ?>
		<?php echo $form->textField($model,'rating',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'rating'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'long'); ?>
		<?php echo $form->textField($model,'long',array('size'=>45,'maxlength'=>45 ,'readonly'=>'readonly')); ?>
		<?php echo $form->error($model,'long'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lat'); ?>
		<?php echo $form->textField($model,'lat',array('size'=>45,'maxlength'=>45 ,'readonly'=>'readonly')); ?>
		<?php echo $form->error($model,'lat'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'loc'); ?>
		<div id="googleMap" style="width:100%;height:500px;"></div>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
var map;
	
	<?php if(!$model->isNewRecord):?>
		var myCenter = new google.maps.LatLng(document.getElementById("BusinessUnit_lat").value, document.getElementById("BusinessUnit_long").value);
		var zoom = 5;
	<?php else:?>
		var myCenter = new google.maps.LatLng(34.82641, 32.22986);
		var zoom = 2;
	<?php endif;?>
	
var marker;

$(document).ready(function() {
	
	if($('#BusinessUnit_accid').find("option:selected").attr('sp') > 0){$('#PackagesDiv').hide();}
	
	
	$('#BusinessUnit_accid').change(function(e) {
	  if($('#BusinessUnit_accid').find("option:selected").attr('sp') > 0){$('#PackagesDiv').hide();}
	  else{$('#PackagesDiv').show();}
	});	
		
});



function initialize()
{
	var mapProp = {
	  center:myCenter,
	  zoom:zoom,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	
	  map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
	  marker = new google.maps.Marker({
		  map:map,
		  draggable:true,
		  animation: google.maps.Animation.DROP,
		  position: myCenter
	  });
  //google.maps.event.addListener(marker, 'click', toggleBounce);
  google.maps.event.addListener(marker, 'click', function(event) {
    toggleBounce(event.latLng);
  });
  //google.maps.event.addListener(map, 'click', function(event) {
   // placeMarker(event.latLng);
  //});
	
}

function toggleBounce(location) {

	  if (marker.getAnimation() != null) {
	    marker.setAnimation(null);
	  } else {
	    marker.setAnimation(google.maps.Animation.BOUNCE);
	    placeMarker(location);
	  }
}

function placeMarker(location) {
	
	//  var infowindow = new google.maps.InfoWindow({
	  //  content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
	 // });
	 // infowindow.open(map,marker);
	  document.getElementById("BusinessUnit_lat").value = location.lat();
	  document.getElementById("BusinessUnit_long").value = location.lng();
}

google.maps.event.addDomListener(window, 'load', initialize);

function FuncRegenerate(){
	var CodeRegenerate = '';
	CodeRegenerate = <?php echo "'".md5(uniqid(mt_rand(),true))."'";?>;
	document.getElementById("BusinessUnit_apiKey").value = CodeRegenerate;
}		
	//function doClose(e) 
	//{
	//    if (!e) e = window.event; 
	
	//    if (e.keyCode) 
	//    {
	 //       if (e.keyCode == "27") document.getElementById('PopLoc').style.display = "none";
	//	}
	//	else if (e.charCode) 
	//	{
	 //   	if (e.charCode == "27") document.getElementById('PopLoc').style.display = "none";
	//    }
	//}
	//document.onkeydown = doClose;
</script>