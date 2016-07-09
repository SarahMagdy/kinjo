<?php
/* @var $this CpanelController */
/* @var $model Cpanel */

$this->breadcrumbs=array(
	'Cpanels'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cpanel', 'url'=>array('index')),
	array('label'=>'Manage Cpanel', 'url'=>array('admin')),
);
?>

<h1>Create Cpanel</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>