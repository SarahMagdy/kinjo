<?php
/* @var $this CpanelController */
/* @var $data Cpanel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cp_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cp_id), array('view', 'id'=>$data->cp_id)); ?>
	
	
	<?php 
		
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/cpanel/thumbnails/'.$data->photo , "photo" ,array('style'=>'float:right;'));
		if($data->photo == 'default.jpg'){
			echo $imghtml.'<div style="float:right;">Please Upload Photo.</div>';
		}else{
			echo CHtml::link($imghtml, array('Common/MyImgsCrop','mName'=>'Cpanel' ,'mID'=>$data->cp_id ,'ProImgID'=>''));
		}
		
	?>
	
	
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buid')); ?>:</b>
	<?php //echo CHtml::encode($data->buid); ?>
	<?php // echo ( $data->bu )? CHtml::link(CHtml::encode($data->bu->title) , array('businessUnit/view', 'id'=>$data->bu) ) : '--';
		if(Yii::app()->session['User']['UserType']=='admin'){
			$Sql = "SELECT accid , fname
					FROM bu_accounts
			 		WHERE accid = ".$data->buid;
		
			$Data = Yii::app()->db->createCommand($Sql)->queryRow();
			echo CHtml::encode($Data['fname']);
		}else if(Yii::app()->session['User']['UserType']=='owner'){
			$Sql = "SELECT buid , title
					FROM business_unit
			 		WHERE buid = ".$data->buid;
		
			$Data = Yii::app()->db->createCommand($Sql)->queryRow();
			echo CHtml::encode($Data['title']);
		}
	
	?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo')); ?>:</b>
	<?php echo CHtml::encode($data->photo); ?>
	<br />-->
	<b><?php echo CHtml::encode($data->getAttributeLabel('role_id')); ?>:</b>
	<?php echo CHtml::encode($data->role->role_name); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fname')); ?>:</b>
	<?php echo CHtml::encode($data->fname); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('lname')); ?>:</b>
	<?php echo CHtml::encode($data->lname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>