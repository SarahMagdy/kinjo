<?php
/* @var $this CatsubController */
/* @var $model Catsub */

$this->breadcrumbs=array(
	'Catsubs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Catsub', 'url'=>array('index')),
	array('label'=>'Manage Catsub', 'url'=>array('admin')),
);
?>

<h1>Create Catsub</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'type'=>$type)); ?>