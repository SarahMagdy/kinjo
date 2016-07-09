<?php
/* @var $this BuAccountsController */
/* @var $data BuAccounts */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('accid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->accid), array('view', 'id'=>$data->accid)); ?>
	
	
	<?php //echo CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/bu_accounts/thumbnails/'.$data->photo , "photo" ,array('style'=>'float:right;')); 
	     ?> 
	 
	     <?php 
	     	
	     	$imghtml=CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/bu_accounts/thumbnails/'.$data->photo , "photo" ,array('style'=>'float:right;'));
			  // echo CHtml::link($imghtml, array('view', 'id'=>$data->accid));
			  // echo CHtml::link($imghtml, array('Common/MyImgsCrop','id'=>$data->accid));
			if($data->photo == 'default.jpg'){
				echo $imghtml.'<div style="float:right;">Please Upload Photo.</div>';
			}else{
				echo CHtml::link($imghtml, array('Common/MyImgsCrop','mName'=>'BuAccounts' ,'mID'=>$data->accid ,'ProImgID'=>''));
		 	}// , 'modelCol'=>'photo' , 'tblName'=>'bu_accounts' , 'tblID'=>'accid'
		 	// MyImgsCrop
		 ?>
	
	<br />


	<!--<b><?php // echo CHtml::encode($data->getAttributeLabel('bill_cycle_id')); ?>:</b>
	<?php // echo CHtml::encode($data->bill_cycle_id); ?>
	<br />-->
	
	<b>
		<?php echo CHtml::encode($data->getAttributeLabel('special_deal_id')); ?>:</b>
		<?php 
			// echo CHtml::encode($data->special_deal_id);
			// echo CHtml::encode($data->specialDeal->sp_d_title);
			// echo CHtml::link($data->specialDeal->sp_d_title, "/index.php/SpecialDeals/$data->special_deal_id");
			$SP_deal = Yii::app()->db->createCommand("SELECT sp_d_title FROM special_deals
					  					   WHERE sp_d_id =".$data->special_deal_id)->queryRow();
			if(!empty($SP_deal)){
				echo CHtml::link($SP_deal['sp_d_title'] , "/index.php/SpecialDeals/$data->special_deal_id" );
			}else{
				echo '--';
			}
			 
		?>
	<br />
	


	<b><?php echo CHtml::encode($data->getAttributeLabel('fname')); ?>:</b>
	<?php echo CHtml::encode($data->fname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lname')); ?>:</b>
	<?php echo CHtml::encode($data->lname); ?>
	<br />	

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->country->name); ?>
	<br />	
	

	<b><?php echo CHtml::encode($data->getAttributeLabel('gender')); ?>:</b>
	<?php echo CHtml::encode($data->gender); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />
	
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php //echo CHtml::encode($data->status); 
		if($data->status == 1){echo 'active';}elseif($data->status == 2){echo 'disabled';}else{echo '--';}
	?>
	<br />
	
	
	
	<!--<b><?php //echo CHtml::encode($data->getAttributeLabel('photo')); ?></b>
	<?php //echo CHtml::encode($data->photo); ?>
	<?php // echo CHtml::image(Yii::app()>request>baseUrl.'/images/upload/bu_accounts/thumbnails/'.$data->photo,"photo",array('style'=>'float:right;')); ?>
	
	<br />-->
	
	
	
	
	<?php /*
	

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	

	<b><?php echo CHtml::encode($data->getAttributeLabel('tel')); ?>:</b>
	<?php echo CHtml::encode($data->tel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bu_acc_TypeID')); ?>:</b>
	<?php echo CHtml::encode($data->bu_acc_TypeID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	*/ ?>

</div>