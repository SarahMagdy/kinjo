<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */

$this->breadcrumbs=array(
	'Billing Cycles'=>array('index'),
	$model->bcid=>array('view','id'=>$model->bcid),
	'Update',
);

$this->menu=array(
	array('label'=>'List BillingCycle', 'url'=>array('index')),
	array('label'=>'Create BillingCycle', 'url'=>array('create')),
	array('label'=>'View BillingCycle', 'url'=>array('view', 'id'=>$model->bcid)),
	array('label'=>'Manage BillingCycle', 'url'=>array('admin')),
);
?>

<h1>Update BillingCycle <?php echo $model->bcid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>