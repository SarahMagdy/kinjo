<?php
/* @var $this OffersController */
/* @var $model Offers */

$this->breadcrumbs=array(
	'Offers'=>array('index'),
	$model->title=>array('view','id'=>$model->ofid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Offers', 'url'=>array('index')),
	array('label'=>'Create Offers', 'url'=>array('create')),
	array('label'=>'View Offers', 'url'=>array('view', 'id'=>$model->ofid)),
	array('label'=>'Manage Offers', 'url'=>array('admin')),
);
?>

<h1>Update Offers <?php echo $model->ofid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>