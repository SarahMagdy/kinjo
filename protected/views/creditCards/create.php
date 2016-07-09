<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */

$this->breadcrumbs=array(
	'Credit Cards'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CreditCards', 'url'=>array('index')),
	array('label'=>'Manage CreditCards', 'url'=>array('admin')),
);
?>

<h1>Create CreditCards</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>