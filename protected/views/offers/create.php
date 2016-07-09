<?php
/* @var $this OffersController */
/* @var $model Offers */

$this->breadcrumbs=array(
	'Offers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Offers', 'url'=>array('index')),
	array('label'=>'Manage Offers', 'url'=>array('admin')),
);
?>

<h1>Create Offers</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>