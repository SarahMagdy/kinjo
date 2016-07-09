<?php
/* @var $this CpanelController */
/* @var $model Cpanel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'MSG-password-form',
	'enableAjaxValidation'=>false,
	
)); ?>

	<div><?= $msg?></div>



<?php $this->endWidget(); ?>

</div>