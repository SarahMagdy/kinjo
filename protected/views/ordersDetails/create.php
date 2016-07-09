<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrdersDetails', 'url'=>array('index')),
	array('label'=>'Manage OrdersDetails', 'url'=>array('admin')),
);
?>

<h1>Create OrdersDetails</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>