<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */

$this->breadcrumbs=array(
	'Business Units'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BusinessUnit', 'url'=>array('index')),
	array('label'=>'Manage BusinessUnit', 'url'=>array('admin')),
);
?>

<h1>Create BusinessUnit</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'AccData'=>$AccData)); ?>