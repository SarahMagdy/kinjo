<?php
/* @var $this M_OrdersController */
/* @var $model M_Orders */

$this->breadcrumbs=array(
	'M  Orders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List M_Orders', 'url'=>array('index')),
	array('label'=>'Manage M_Orders', 'url'=>array('admin')),
);
?>

<h1>Create M_Orders</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>