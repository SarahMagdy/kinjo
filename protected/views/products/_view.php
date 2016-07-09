<?php
/* @var $this ProductsController */
/* @var $data Products */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('pid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->pid), array('view', 'id'=>$data->pid)); ?>
	<br />
<!--
	<b><?php echo CHtml::encode($data->getAttributeLabel('business_unit_title')); ?>:</b>
	   <?php //echo CHtml::encode($data->buid); ?>
	   <?php echo (!empty($data->bu)) ? CHtml::link($data->bu->title, "/index.php/businessUnit/$data->buid") : 'N/A';?>
  
	<br />
-->	 
	<b><?php echo CHtml::encode($data->getAttributeLabel('csid')); ?>:</b>
	   <?php //echo CHtml::encode($data->csid); ?>
	   <?php echo (!empty($data->cs)) ?  CHtml::link($data->cs->title, "/index.php/catsub/$data->csid") : 'N/A';?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sku')); ?>:</b>
	<?php echo CHtml::encode($data->sku); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discription')); ?>:</b>
	<?php echo CHtml::encode($data->discription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('barcode')); ?>:</b>
	<?php echo CHtml::encode($data->barcode); ?>
	<br />
	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('rating')); ?>:</b>
	<?php echo CHtml::encode($data->rating); ?>-->
	<?php 
	$this->widget('CStarRating',array(
	            'name'=>'rating'.$data->pid,
	            'starCount'=>5,
	            'value'=>$data->rating,
	            'minRating'=>1,
	            'maxRating'=>5,
	            'readOnly'=>true,
	            // 'titles'=>array(
	                // '1'=>'Normal',
	                // '2'=>'Average',
	                // '3'=>'OK',
	                // '4'=>'Good',
	                // '5'=>'Excellent'
	            // ),
	          ));
			  
	?>
	<br />
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('instock')); ?>:</b>
	<?php echo CHtml::encode($data->instock); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount')); ?>:</b>
	<?php echo CHtml::encode($data->discount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qrcode')); ?>:</b>
	<?php echo CHtml::encode($data->qrcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nfc')); ?>:</b>
	<?php echo CHtml::encode($data->nfc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hash')); ?>:</b>
	<?php echo CHtml::encode($data->hash); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bookable')); ?>:</b>
	<?php echo CHtml::encode($data->bookable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>