<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */

$this->breadcrumbs=array(
	'Special Deals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SpecialDeals', 'url'=>array('index')),
	array('label'=>'Manage SpecialDeals', 'url'=>array('admin')),
);
?>

<h1>Create SpecialDeals</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>