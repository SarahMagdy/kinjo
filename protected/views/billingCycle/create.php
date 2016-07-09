<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */

$this->breadcrumbs=array(
	'Billing Cycles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BillingCycle', 'url'=>array('index')),
	array('label'=>'Manage BillingCycle', 'url'=>array('admin')),
);
?>

<h1>Create BillingCycle</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>