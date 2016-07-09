<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */

$this->breadcrumbs=array(
	'Pd Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PdConfig', 'url'=>array('index')),
	array('label'=>'Manage PdConfig', 'url'=>array('admin')),
);
?>

<h1>Create PdConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>