<?php
/* @var $this CreditCardsController */
/* @var $data CreditCards */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cr_card_id), array('view', 'id'=>$data->cr_card_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_owner_id')); ?>:</b>
	<?php // echo CHtml::encode($data->cr_card_owner_id); ?>
	<?php echo CHtml::encode($data->crCardOwner->fname." ".$data->crCardOwner->lname); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_namecard')); ?>:</b>
	<?php echo CHtml::encode($data->cr_card_namecard); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_credit')); ?>:</b>
	<?php // echo CHtml::encode($data->cr_card_credit); 
		 echo $this->getCreditCard($data , $data->cr_card_id);
	?>
	<br />

	<!-- <b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_cvv')); ?>:</b>
	<?php echo CHtml::encode($data->cr_card_cvv); ?>
	<br /> -->

	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_expirationDate')); ?>:</b>
	<?php echo CHtml::encode($data->cr_card_expirationDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cr_card_rank')); ?>:</b>
	<?php // echo CHtml::encode($data->cr_card_rank); 
		if($data->cr_card_rank == 1){
			echo 'Primary Card'; 
		}elseif($data->cr_card_rank == 2){
			echo 'Secondary Card';
		}
	?>
	<br />


</div>