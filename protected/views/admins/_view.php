<?php
/* @var $this AdminsController */
/* @var $data Admins */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('adid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->adid), array('view', 'id'=>$data->adid)); ?>
	<?php 
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/upload/admins/thumbnails/'.$data->photo,"photo",array('style'=>'float:right;'));
		if($data->photo == 'default.jpg'){
			echo $imghtml.'<div style="float:right;">Please Upload Photo.</div>';
		}else{
			echo CHtml::link($imghtml, array('Common/MyImgsCrop','mName'=>'Admins' ,'mID'=>$data->adid ,'ProImgID'=>''));
		}
	?>
	
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fname')); ?>:</b>
	<?php echo CHtml::encode($data->fname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lname')); ?>:</b>
	<?php echo CHtml::encode($data->lname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php /* echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo')); ?>:</b>
	<?php echo CHtml::encode($data->photo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php 
	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>