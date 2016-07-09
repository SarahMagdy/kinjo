<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
	$ISAjax = Yii::app()->request->isAjaxRequest;
	

?>

<h2>Error <?php echo $code; ?></h2>
<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
<script>
	/*
	alert($('#ISAjax').val());
		if($('#ISAjax').val() == '1'){
			var Error = $('div.error').html();
			alert(Error);
		}*/
	
</script>