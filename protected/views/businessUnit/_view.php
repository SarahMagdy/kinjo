<?php
/* @var $this BusinessUnitController */
/* @var $data BusinessUnit */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('buid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->buid), array('view', 'id'=>$data->buid)); ?>
	<!--<?php echo CHtml::encode($data->getAttributeLabel('logo')); ?>:</b>
	<?php echo CHtml::encode($data->logo); ?>-->
	<?php
		$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/images/upload/business_unit/thumbnails/'.$data->logo,"logo",array('style'=>'float:right;')); 
		if($data->logo == 'default.jpg'){
			echo $imghtml.'<div style="float:right;">Please Upload Photo.</div>';
		}else{
			echo CHtml::link($imghtml, array('Common/MyImgsCrop','mName'=>'BusinessUnit' ,'mID'=>$data->buid ,'ProImgID'=>''));
		}
	?>
	
	
	<br />
	<?php if(Yii::app()->session['User']['UserType']== 'admin'):?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('accid')); ?>:</b>
	<!--<?php //echo CHtml::encode($data->acc->fname.' '.$data->acc->lname); ?>-->
	<?php echo CHtml::link(CHtml::encode($data->acc->fname." ".$data->acc->lname), "/index.php/buAccounts/".$data->accid) ?>
	<br />
	<?php endif;?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('currency_code')); ?>:</b>
	<?php echo CHtml::encode($data->currency_code); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('long')); ?>:</b>
	<?php echo CHtml::encode($data->long); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lat')); ?>:</b>
	<?php echo CHtml::encode($data->lat); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	  <?php $active = $data->active == 0?'Active':'Not Active';?>
	<?php echo CHtml::encode($active); ?>
	<br />
	
	<?php if($data->pkg_id > 0){
			$Pkg =	Yii::app()->db->createCommand("SELECT title FROM packages WHERE pkgid = ".$data->pkg_id)->queryRow();
			echo '<b>'.CHtml::encode($data->getAttributeLabel('pkg_id')).' </b>';
			echo CHtml::encode($Pkg['title']).'<br />';
		}
	?>
	<?php 
	$this->widget('CStarRating',array(
	            'name'=>'rating'.$data->buid,
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
<!--
	<b><?php echo CHtml::encode($data->getAttributeLabel('membership')); ?>:</b>
		<?php 
			$data->membership == 3 ? $membership="membership_3" : ($data->membership == 2 ? $membership="membership_2" : $membership="membership_1");
			echo CHtml::encode($membership);
		 ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
		<?php 
			$data->type == 3 ? $type="type_3" : ($data->type == 2 ? $type="type_2" : $type="type_1");
			echo CHtml::encode($type); 
		?>
	<br />-->
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('urlid')); ?>:</b>
	<?php echo CHtml::encode($data->urlid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('site')); ?>:</b>
	<?php echo CHtml::encode($data->site); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('statid')); ?>:</b>
	<?php echo CHtml::encode($data->statid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apiKey')); ?>:</b>
	<?php echo CHtml::encode($data->apiKey); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rating')); ?>:</b>
	<?php echo CHtml::encode($data->rating); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>