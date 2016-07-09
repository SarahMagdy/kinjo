<?php
/* @var $this CatsubController */
/* @var $data Catsub */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('csid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->csid), array('view', 'id'=>$data->csid)); ?>
	

     <?php 
     	$RealArr = Globals::ReturnGlobals();
		$RealPath = $RealArr['ImgSerPath'].'catsub/thumbnails/';
		
     	$imghtml=CHtml::image('http://'.$RealPath.$data->img_thumb , "photo" ,array('style'=>'float:right;'));
		
		if($data->img_url == 'default.jpg'){
			echo $imghtml.'<div style="float:right;">Please Upload Photo.</div>';
		}else{
			echo CHtml::link($imghtml, array('Common/MyImgsCrop','mName'=>'Catsub' ,'mID'=>$data->csid ,'ProImgID'=>''));
		}
		  
	 ?>
	
	
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo ( $data->parent )? CHtml::encode($data->parent->title) : '--';?>

	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('catsub_buid')); ?>:</b>
	<?php //echo CHtml::encode($data->catsub_buid); ?>
	<?php echo ( $data->catsubBu )? CHtml::link(CHtml::encode($data->catsubBu->title) , array('businessUnit/view', 'id'=>$data->catsub_buid) ) : '--'; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('desription')); ?>:</b>
	<?php echo CHtml::encode($data->desription); ?>
	<br />

	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('img_thumb')); ?>:</b>
	<?php echo CHtml::encode($data->img_thumb); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('img_url')); ?>:</b>
	<?php echo CHtml::encode($data->img_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />


</div>